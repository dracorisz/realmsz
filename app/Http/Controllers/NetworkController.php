<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Inertia\Inertia;

class NetworkController extends Controller
{
	public function index()
	{
		return Inertia::render('App/Network', ['users' => User::where('organization_id', Auth::user()->organization_id)->get()]);
	}
}
