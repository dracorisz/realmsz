<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\FocusSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Inertia\Inertia;

class FocusController extends Controller
{
    public function index()
    {
        // $currentSession = FocusSession::with('item')
        //     ->where('user_id', Auth::id())
        //     ->whereNull('ended_at')
        //     ->first();

        $items = Item::with(['category', 'priority', 'status'])
            ->where('organization_id', Auth::user()->organization_id)
            ->orWhere('organization_id', 0)
            ->where('archived', 0)
            ->where('status_id', 1)
            ->orderBy('date', 'ASC')
            ->get();

        return Inertia::render('App/Focus/Main', [
            'items' => $items,
            // 'currentSession' => $currentSession,
            // 'stats' => [
            //     'totalSessions' => FocusSession::where('user_id', Auth::id())->count(),
            //     'totalTime' => FocusSession::where('user_id', Auth::id())
            //         ->whereNotNull('ended_at')
            //         ->sum('duration'),
            //     'averageDuration' => FocusSession::where('user_id', Auth::id())
            //         ->whereNotNull('ended_at')
            //         ->avg('duration'),
            // ]
        ]);
    }

    public function startSession(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'duration' => 'required|integer|min:1|max:120'
        ]);

        // End any existing session
        FocusSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->update(['ended_at' => now()]);

        $session = FocusSession::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'duration' => $request->duration,
            'started_at' => now()
        ]);

        return response()->json([
            'session' => $session->load('item')
        ]);
    }

    public function endSession()
    {
        $session = FocusSession::where('user_id', Auth::id())
            ->whereNull('ended_at')
            ->first();

        if (!$session) {
            return response()->json(['message' => 'No active session found'], 404);
        }

        $session->update([
            'ended_at' => now(),
            'duration' => Carbon::parse($session->started_at)->diffInMinutes(now())
        ]);

        return response()->json([
            'session' => $session->load('item')
        ]);
    }

    public function getStats()
    {
        $today = FocusSession::where('user_id', Auth::id())
            ->whereDate('started_at', today())
            ->whereNotNull('ended_at')
            ->sum('duration');

        $week = FocusSession::where('user_id', Auth::id())
            ->whereBetween('started_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereNotNull('ended_at')
            ->sum('duration');

        // Calculate best streak
        $sessions = FocusSession::where('user_id', Auth::id())
            ->whereNotNull('ended_at')
            ->orderBy('started_at')
            ->get();

        $bestStreak = 0;
        $currentStreak = 0;
        $lastDate = null;

        foreach ($sessions as $session) {
            $date = Carbon::parse($session->started_at)->startOfDay();
            
            if ($lastDate === null) {
                $currentStreak = 1;
            } elseif ($date->diffInDays($lastDate) === 1) {
                $currentStreak++;
            } else {
                $currentStreak = 1;
            }
            
            $bestStreak = max($bestStreak, $currentStreak);
            $lastDate = $date;
        }

        return response()->json([
            'today' => [
                'totalMinutes' => $today
            ],
            'week' => [
                'totalMinutes' => $week
            ],
            'bestStreak' => $bestStreak
        ]);
    }
} 