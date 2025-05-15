<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class StatusController extends Controller
{
	public function index()
	{
		$statuses = Status::where('organization_id', Auth::user()->organization_id)
			->orderBy('name')
			->get();

		return response()->json($statuses);
	}

	public function store(Request $request)
	{
		try {
			Status::create($request->all());
			return 'Status added.';
		} catch (Exception $e) {
			Log::info('Exception during status creation:', ['exception' => $e->getMessage()]);
		}
	}

	public function update(Request $request)
	{
		try {
			$item = Status::find($request->id);
			$item->update($request->all());
			return 'Status updated.';
		} catch (Exception $e) {
			Log::info('Exception during status change:', ['exception' => $e->getMessage()]);
		}
	}

	public function destroy(Request $request)
	{
		try {
			Status::destroy($request->id);
			return 'Status deleted.';
		} catch (Exception $e) {
			Log::info('Exception during status deletion:', ['exception' => $e->getMessage()]);
		}
	}
}
