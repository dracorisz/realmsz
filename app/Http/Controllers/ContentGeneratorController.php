<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Services\ContentGeneratorService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentGeneratorController extends Controller
{
    protected $contentGenerator;

    public function __construct(ContentGeneratorService $contentGenerator)
    {
        $this->contentGenerator = $contentGenerator;
    }

    public function preview(Package $package)
    {
        $preview = $this->contentGenerator->previewContent($package);
        
        return Inertia::render('Content/Preview', [
            'preview' => $preview,
            'package' => $package
        ]);
    }

    public function generate(Package $package)
    {
        $result = $this->contentGenerator->generateContent($package);
        
        if (!$this->contentGenerator->validateContent($result['content'])) {
            return back()->with('error', 'Failed to generate content: Invalid content structure');
        }

        return back()->with('success', 'Content generated successfully!');
    }

    public function show(Package $package)
    {
        $content = $this->contentGenerator->previewContent($package);
        
        return Inertia::render('Content/Show', [
            'content' => $content,
            'package' => $package
        ]);
    }
} 