<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Perfect for getting started with Realmsz',
                'price' => 9.99,
                'billing_period' => 'monthly',
                'features' => [
                    'Access to basic modules',
                    '5GB storage',
                    'Basic support',
                    'Community access',
                ],
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For professionals and small teams',
                'price' => 29.99,
                'billing_period' => 'monthly',
                'features' => [
                    'All Basic features',
                    '50GB storage',
                    'Priority support',
                    'Advanced analytics',
                    'Team collaboration',
                    'Custom branding',
                ],
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large organizations and businesses',
                'price' => 99.99,
                'billing_period' => 'monthly',
                'features' => [
                    'All Pro features',
                    'Unlimited storage',
                    '24/7 support',
                    'Custom integrations',
                    'API access',
                    'Dedicated account manager',
                    'Advanced security',
                ],
            ],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
} 