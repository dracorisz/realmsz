<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'description' => 'Perfect for getting started with Realmsz',
                'price' => 0,
                'features' => [
                    'Access to basic NFT marketplace',
                    'Create up to 5 NFTs per month',
                    'Basic AI image generation',
                    'Community access',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Pro',
                'description' => 'For serious creators and collectors',
                'price' => 9.99,
                'features' => [
                    'Unlimited NFT creation',
                    'Advanced AI image generation',
                    'Priority support',
                    'Custom branding',
                    'Analytics dashboard',
                ],
                'is_active' => true,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'For businesses and large-scale operations',
                'price' => 49.99,
                'features' => [
                    'Everything in Pro',
                    'Custom API integration',
                    'Dedicated support team',
                    'White-label solution',
                    'Advanced security features',
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
} 