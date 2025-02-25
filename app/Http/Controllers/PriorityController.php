<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Priority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class PriorityController extends Controller
{
	public function index()
	{
		return Priority::where('organization_id', Auth::user()->organization_id)->with('icon')->get();
	}

	public function store(Request $request)
	{
		try {
			Priority::create($request->all());
			return 'Priority added.';
		} catch (Exception $e) {
			Log::info('Exception during priority creation:', ['exception' => $e->getMessage()]);
		}
	}

	public function update(Request $request)
	{
		try {
			$item = Priority::find($request->id);
			$item->update($request->all());
			return 'Priority updated.';
		} catch (Exception $e) {
			Log::info('Exception during priority change:', ['exception' => $e->getMessage()]);
		}
	}

	public function destroy(Request $request)
	{
		try {
			Priority::destroy($request->id);
			return 'Priority deleted.';
		} catch (Exception $e) {
			Log::info('Exception during priority deletion:', ['exception' => $e->getMessage()]);
		}
	}
}
