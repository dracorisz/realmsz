<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Chat;
use Inertia\Inertia;
use Exception;

class ChatController extends Controller
{
    public function index()
    {
		return Inertia::render('App/Milai/Main', [
			'chats' => Chat::where('user_id', Auth::user()->id)->with('prompts')->orderBy('created_at', 'desc')->get(),
		]);
    }

    public function show(Chat $chat)
    {
        return $chat->load('prompts');
    }

    public function store(Request $request)
    {
        $request->validate([
            'chatName' => 'required|string',
            'firstPrompt' => 'required|string',
        ]);

        $chat = Chat::create([
            'user_id' => Auth::user()->id,
            'name' => $request->chatName,
            'visible' => true,
        ]);

        return response()->json($chat, 201);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'chat' => 'required|integer',
        ]);

        try {
            $chat = Chat::with('prompts')->find($request->chat);
    
            if ($chat) {
                foreach ($chat->prompts as $prompt) $prompt->delete();
                $chat->delete();
    
                return response()->json(['message' => 'Chat deleted.'], 200);
            } else {
                return response()->json(['message' => 'Chat not found.'], 404);
            }
        } catch (Exception $e) {
            Log::info('Exception during chat deletion:', ['exception' => $e->getMessage()]);
            return response()->json(['message' => 'Error deleting chat.'], 500);
        }
    }
}
