<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prompt;

class PromptController extends Controller
{
    public function index(Request $request)
	{
		return Prompt::where('chat_id', $request->chat_id)->get();
	}
    
    public function store(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|integer',
            'question' => 'required|string',
            'status' => 'nullable|integer',
        ]);
        
        $prompt = Prompt::create([
            'chat_id' => $request->chat_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'status' => $request->status,
        ]);
        
        return response()->json($prompt, 201);
    }
}
