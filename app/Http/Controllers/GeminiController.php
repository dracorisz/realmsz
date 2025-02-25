<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class GeminiController extends Controller
{
    public function text(Request $request)
    {
        $client = new Client();
        $secret = config('services.gemini.secret');

        try {
            $response = $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $secret, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $request->all(),
            ]);

            return response()->json(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    public function chatName(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        // Example: Generate a name using part of the prompt and answer (simulated here)
        $name = 'Milai: ' . substr($request->prompt, 0, 15);
        return response()->json(['chatName' => $name], 200);
    }
}