<?php

return [
    'groups' => [
        'web' => [
            'welcome',
            'ecosystem',
            'partnership',
            'roadmap',
            'faq',
            'ico',
            'ipo',
            'packages',
            'privacy-policy',
            'terms-of-service',
        ],
        'jetstream' => [
            'profile',
            'other-browser-sessions',
            'current-user-photo',
            'current-user.destroy',
            'api-tokens.*',
            'teams.*',
            'team-members.*',
        ],
    ],
    'except' => [
        'debugbar.*',
        'ignition.*',
        'horizon.*',
        'telescope.*',
        'sanctum.*',
        '_ignition.*',
    ],
    'cache' => true,
    'url' => env('APP_URL', 'http://localhost'),
    'port' => env('APP_PORT', 80),
    'defaults' => [
        'middleware' => ['web'],
    ],
    'middleware' => ['web'],
    'domain' => null,
    'port' => null,
    'where' => [],
    'base' => null,
    'absolute' => true,
]; 