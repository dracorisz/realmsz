<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use App\Models\ItemUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NetworkController extends Controller
{
	public function index(Request $request)
	{
		$query = User::query()
			->with(['organization', 'items'])
			->where('id', '!=', Auth::id());

		// Filter by role if specified
		if ($request->has('role') && $request->role !== 'all') {
			$query->where('role', $request->role);
		}

		// Filter by organization if specified
		if ($request->has('organization_id')) {
			$query->whereHas('organization', function ($q) use ($request) {
				$q->where('organizations.id', $request->organization_id);
			});
		}

		// Filter by visibility preference
		$query->where('show_in_network', true);

		$users = $query->paginate(12);
		$organizations = Organization::all();

		return Inertia::render('App/Network', [
			'users' => $users,
			'organizations' => $organizations,
			'filters' => [
				'role' => $request->role ?? 'all',
				'organization_id' => $request->organization_id
			]
		]);
	}

	public function updateVisibility(Request $request)
	{
		$request->validate([
			'show_in_network' => 'required|boolean',
		]);

		$user = Auth::user();
		User::where('id', $user->id)->update(['show_in_network' => $request->show_in_network]);

		return response()->json(['message' => 'Visibility updated successfully']);
	}

	public function addToTask(Request $request)
	{
		$request->validate([
			'user_id' => 'required|exists:users,id',
			'item_id' => 'required|exists:items,id',
		]);

		$user = User::find($request->user_id);
		
		ItemUser::firstOrCreate([
			'user_id' => $request->user_id,
			'item_id' => $request->item_id,
		], [
			'role' => $user->role,
		]);

		return response()->json(['message' => 'User added to task successfully']);
	}

	public function removeFromTask(Request $request)
	{
		$request->validate([
			'user_id' => 'required|exists:users,id',
			'item_id' => 'required|exists:items,id',
		]);

		ItemUser::where('user_id', $request->user_id)
			->where('item_id', $request->item_id)
			->delete();

		return response()->json(['message' => 'User removed from task successfully']);
	}
}
