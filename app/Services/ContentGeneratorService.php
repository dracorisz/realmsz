<?php

namespace App\Services;

use App\Models\Package;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ContentGeneratorService
{
    protected $templates = [
        'basic' => [
            'title' => 'Basic Content Template',
            'description' => 'This is a basic content template for the Basic package.',
            'features' => [
                'basic_module_access',
                'basic_storage',
                'basic_support',
                'community_access'
            ]
        ],
        'pro' => [
            'title' => 'Pro Content Template',
            'description' => 'This is a professional content template for the Pro package.',
            'features' => [
                'advanced_module_access',
                'pro_storage',
                'priority_support',
                'advanced_analytics',
                'team_collaboration',
                'custom_branding'
            ]
        ],
        'enterprise' => [
            'title' => 'Enterprise Content Template',
            'description' => 'This is an enterprise-grade content template for the Enterprise package.',
            'features' => [
                'enterprise_module_access',
                'unlimited_storage',
                'premium_support',
                'custom_integrations',
                'api_access',
                'dedicated_manager',
                'advanced_security'
            ]
        ]
    ];

    public function generateContent(Package $package)
    {
        $template = $this->getTemplate($package->slug);
        $content = $this->processTemplate($template, $package);
        
        return $this->saveContent($content, $package);
    }

    protected function getTemplate($packageSlug)
    {
        return $this->templates[$packageSlug] ?? $this->templates['basic'];
    }

    protected function processTemplate($template, Package $package)
    {
        $content = [
            'title' => $template['title'],
            'description' => $template['description'],
            'package_id' => $package->id,
            'features' => $this->mapFeatures($package->features, $template['features']),
            'metadata' => [
                'generated_at' => now(),
                'package_slug' => $package->slug,
                'package_name' => $package->name
            ]
        ];

        return $content;
    }

    protected function mapFeatures($packageFeatures, $templateFeatures)
    {
        $mappedFeatures = [];
        
        foreach ($packageFeatures as $feature) {
            if (in_array($feature, $templateFeatures)) {
                $mappedFeatures[] = [
                    'name' => $feature,
                    'enabled' => true,
                    'description' => $this->getFeatureDescription($feature)
                ];
            }
        }

        return $mappedFeatures;
    }

    protected function getFeatureDescription($feature)
    {
        $descriptions = [
            'basic_module_access' => 'Access to basic application modules',
            'basic_storage' => '5GB of storage space',
            'basic_support' => 'Standard support during business hours',
            'community_access' => 'Access to community forums and resources',
            'advanced_module_access' => 'Access to all advanced modules',
            'pro_storage' => '50GB of storage space',
            'priority_support' => 'Priority support with faster response times',
            'advanced_analytics' => 'Advanced analytics and reporting tools',
            'team_collaboration' => 'Team collaboration features',
            'custom_branding' => 'Custom branding options',
            'enterprise_module_access' => 'Access to all enterprise modules',
            'unlimited_storage' => 'Unlimited storage space',
            'premium_support' => '24/7 premium support',
            'custom_integrations' => 'Custom API integrations',
            'api_access' => 'Full API access',
            'dedicated_manager' => 'Dedicated account manager',
            'advanced_security' => 'Advanced security features'
        ];

        return $descriptions[$feature] ?? 'Feature description not available';
    }

    protected function saveContent($content, Package $package)
    {
        $filename = 'content/' . $package->slug . '/' . Str::slug($content['title']) . '.json';
        
        Storage::put($filename, json_encode($content, JSON_PRETTY_PRINT));
        
        return [
            'path' => $filename,
            'content' => $content
        ];
    }

    public function validateContent($content)
    {
        $requiredFields = ['title', 'description', 'package_id', 'features', 'metadata'];
        
        foreach ($requiredFields as $field) {
            if (!isset($content[$field])) {
                return false;
            }
        }

        return true;
    }

    public function previewContent(Package $package)
    {
        $template = $this->getTemplate($package->slug);
        return $this->processTemplate($template, $package);
    }
} 