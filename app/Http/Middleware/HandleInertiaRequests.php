<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use Laravel\Jetstream\Features;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        
        return array_merge(parent::share($request), [
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                    'query' => $request->query(),
                    'absolute' => true,
                ]);
            },
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'profile_photo_url' => $user->profile_photo_url,
                    'current_team' => $user->currentTeam,
                    'all_teams' => $user->allTeams(),
                ] : null,
            ],
            'jetstream' => [
                'hasTeamFeatures' => Features::hasTeamFeatures(),
                'hasApiFeatures' => Features::hasApiFeatures(),
                'canCreateTeams' => Features::optionEnabled(Features::teams(), 'create'),
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'type' => fn () => $request->session()->get('type'),
            ],
            'errors' => fn () => $request->session()->get('errors') 
                ? $request->session()->get('errors')->getBag('default')->getMessages() 
                : (object) [],
        ]);
    }
}
