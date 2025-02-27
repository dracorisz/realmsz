<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ItemRequest;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IconController;
use Inertia\Inertia;
use App\Models\Item;
use Exception;

class ItemController extends Controller
{
	public function index()
	{
		$statuses = (new StatusController)->index();
		$priorities = (new PriorityController)->index();
		$categories = (new CategoryController)->index();
		$icons = (new IconController)->index();

		return Inertia::render('App/Plans', [
			'items' => Item::with(['category', 'priority', 'status'])->orderBy('status_id', 'asc')->get(),
			'statuses' => $statuses,
			'priorities' => $priorities,
			'categories' => $categories,
			'icons' => $icons,
		]);
	}

	public function focus()
	{
		$icons = (new IconController)->index();

		return Inertia::render('App/Focus', [
			'items' => Item::with(['category', 'priority', 'status'])->where('archived', 0)->where('status_id', 1)->orderBy('date', 'ASC')->get(),
			'icons' => $icons,
		]);
	}

	public function store(ItemRequest $request)
	{
		try {
			Item::create($request->all());
			return 'Item added.';
		} catch (Exception $e) {
			Log::info('Exception during item creation:', ['exception' => $e->getMessage()]);
		}
	}

	public function duplicate(ItemRequest $request)
	{
		try {
			Item::create($request->all());
			return 'Item duplicated.';
		} catch (Exception $e) {
			Log::info('Exception during item duplication:', ['exception' => $e->getMessage()]);
		}
	}

	public function destroy(Request $request)
	{
		try {
			Item::destroy($request->id);
			return 'Item deleted.';
		} catch (Exception $e) {
			Log::info('Exception during item deletion:', ['exception' => $e->getMessage()]);
		}
	}

	public function archive(Request $request)
	{
		try {
			$item = Item::find($request->id);

			if ($item->archived === 1)
				$item->archived = 0;
			else
				$item->archived = 1;

			$item->save();

			if ($item->archived === 1)
				return 'Item archived.';
			else
				return 'Item unarchived.';
		} catch (Exception $e) {
			Log::info('Exception during item\'s archived status change:', ['exception' => $e->getMessage()]);
		}
	}

	public function update(ItemRequest $request)
	{
		try {
			$item = Item::find($request->id);
			$item->update($request->all());
			return 'Item updated.';
		} catch (Exception $e) {
			Log::info('Exception during item change:', ['exception' => $e->getMessage()]);
		}
	}

	public function status(Request $request)
	{
		try {
			$item = Item::find($request->id);
			$item->status_id = $request->status_id;
			$item->save();
			return 'Status updated.';
		} catch (Exception $e) {
			Log::info('Exception during item\'s status change:', ['exception' => $e->getMessage()]);
		}
	}

	public function priority(Request $request)
	{
		try {
			$item = Item::find($request->id);
			$item->priority_id = $request->priority_id;
			$item->save();
			return 'Priority updated.';
		} catch (Exception $e) {
			Log::info('Exception during item\'s priority change:', ['exception' => $e->getMessage()]);
		}
	}

	public function date(Request $request)
	{
		try {
			$item = Item::find($request->id);
			$item->date = $request->date;
			$item->recurring = $request->recurring;
			$item->recurring_interval = $request->recurring_interval;
			$item->save();
			return 'Date updated.';
		} catch (Exception $e) {
			Log::info('Exception during item\'s date change:', ['exception' => $e->getMessage()]);
		}
	}

	public function export()
	{
		$fileName = 'items.csv';
		$items = Item::where('archived', 0)->with(['category', 'priority', 'status'])->get();

		$headers = array(
			"Content-type" => "text/csv",
			"Content-Disposition" => "attachment; filename=$fileName",
			"Pragma" => "no-cache",
			"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
			"Expires" => "0",
		);

		$columns = array('Title', 'Description', 'Date', 'Status', 'Priority');

		$callback = function () use ($items, $columns) {
			$file = fopen('php://output', 'w');
			fputcsv($file, $columns);

			foreach ($items as $item) {
				$row['Title'] = $item->title;
				$row['Description'] = $item->description;
				$row['Date'] = $item->date;
				$row['Status'] = $item->status['name'];
				$row['Priority'] = $item->priority['name'];

				fputcsv($file, array($row['Title'], $row['Description'], $row['Date'], $row['Status'], $row['Priority']));
			}

			fclose($file);
		};

		return response()->streamDownload($callback, 200, $headers);
	}

	// public function assignment(Request $request)
	// {
	// 	try {
	// 		$item = Item::find(1);
	// 		$item->users()->attach(2); // Assign user with ID 2 to item 1
	// 		$item->users()->detach(2); // Remove user 2 from item 1
	// 		$item->users()->sync([2, 3, 4]); // Removes all previous users and only keeps 2, 3, and 4
	// 		$item->users()->syncWithoutDetaching([5]); // Keeps previous assignments and adds user 5

	// 		return 'Assigned to updated.';
	// 	} catch (Exception $e) {
	// 		Log::info('Exception during item assigned to change:', ['exception' => $e->getMessage()]);
	// 	}
	// }
}
