<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PartnershipInquiry;

class PartnershipController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Send email to both recipients
        Mail::to(['suzy@realmsz.com', 'mila@realmsz.com'])
            ->send(new PartnershipInquiry($validated));

        return back()->with('success', 'Your partnership inquiry has been sent successfully!');
    }
} 