<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Agent;

// use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
// use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use Concerns\ConfirmsTwoFactorAuthentication;
    /**
     * Show the general profile settings screen.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $this->validateTwoFactorAuthenticationState($request);

        return Jetstream::inertia()->render($request, 'App/Profile/Main', [
            'confirmsTwoFactorAuthentication' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            'sessions' => $this->sessions($request)->all(),
        ]);
    }

    /**
     * Get the current sessions.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function sessions(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param  mixed  $session
     * @return \Jenssegers\Agent\Agent
     */
    protected function createAgent($session)
    {
        return tap(new Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }

    public function getActivityStats()
    {
        $user = auth()->user();
        return [
            'tasks_created' => $user->tasks()->count(),
            'total_users' => User::count(),
            'total_tasks' => Task::count(),
            'total_storage' => Storage::sum('size'),
        ];
    }

    /**
     * Get the user's recent activities.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function activities(Request $request)
    {
        $activities = $request->user()->activities()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'description' => $activity->description,
                    'icon' => $activity->icon,
                    'created_at' => $activity->created_at->diffForHumans(),
                ];
            });

        return response()->json(['activities' => $activities]);
    }
}
