<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('organization_id', Auth::user()->organization_id)
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

	public function store(Request $request)
	{
		try {
			Category::create($request->all());
			return 'Category added.';
		} catch (Exception $e) {
			Log::info('Exception during category creation:', ['exception' => $e->getMessage()]);
		}
	}

	public function update(Request $request)
	{
		try {
			$item = Category::find($request->id);
			$item->update($request->all());
			return 'Category updated.';
		} catch (Exception $e) {
			Log::info('Exception during category change:', ['exception' => $e->getMessage()]);
		}
	}

	public function destroy(Request $request)
	{
		try {
			Category::destroy($request->id);
			return 'Category deleted.';
		} catch (Exception $e) {
			Log::info('Exception during category deletion:', ['exception' => $e->getMessage()]);
		}
	}
}
