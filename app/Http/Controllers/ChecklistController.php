<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'title' => 'required|string|max:255',
        ]);

        $checklist = Checklist::create([
            'item_id' => $request->item_id,
            'title' => $request->title,
            'completed' => false,
            'order' => Checklist::where('item_id', $request->item_id)->max('order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Checklist item created successfully',
            'checklist' => $checklist
        ]);
    }

    public function update(Request $request, Checklist $checklist)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'title' => 'required|string|max:255',
            'completed' => 'required|boolean'
        ]);

        // Verify the checklist belongs to the user's organization
        if ($checklist->item->organization_id !== Auth::user()->organization_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $checklist->update([
            'title' => $request->title,
            'completed' => $request->completed
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Checklist item updated successfully',
            'checklist' => $checklist,
            'progress' => Checklist::getProgress($request->item_id)
        ]);
    }

    public function destroy(Checklist $checklist)
    {
        // Verify the checklist belongs to the user's organization
        if ($checklist->item->organization_id !== Auth::user()->organization_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $checklist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Checklist item deleted successfully'
        ]);
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'checklist_id' => 'required|exists:checklists,id',
            'target_id' => 'required|exists:checklists,id'
        ]);

        $checklist = Checklist::findOrFail($request->checklist_id);
        $target = Checklist::findOrFail($request->target_id);

        // Verify both checklists belong to the user's organization
        if ($checklist->item->organization_id !== Auth::user()->organization_id ||
            $target->item->organization_id !== Auth::user()->organization_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $checklist->moveAfter($target);

        return response()->json([
            'success' => true,
            'message' => 'Checklist reordered successfully'
        ]);
    }

    public function markAll(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'completed' => 'required|boolean'
        ]);

        $item = Item::findOrFail($request->item_id);

        // Verify the item belongs to the user's organization
        if ($item->organization_id !== Auth::user()->organization_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $item->checklists()->update(['completed' => $request->completed]);

        return response()->json([
            'success' => true,
            'message' => 'All checklist items marked as ' . ($request->completed ? 'completed' : 'incomplete'),
            'progress' => Checklist::getProgress($request->item_id)
        ]);
    }

    public function clearCompleted(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id'
        ]);

        $item = Item::findOrFail($request->item_id);

        // Verify the item belongs to the user's organization
        if ($item->organization_id !== Auth::user()->organization_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $item->checklists()->where('completed', true)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Completed checklist items cleared',
            'progress' => Checklist::getProgress($request->item_id)
        ]);
    }

    public function index(Request $request, $item)
    {
        $item = Item::findOrFail($item);

        // Verify the item belongs to the user's organization
        if ($item->organization_id !== Auth::user()->organization_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $checklists = $item->checklists()->orderBy('order')->get();

        return response()->json([
            'success' => true,
            'checklists' => $checklists,
            'progress' => Checklist::getProgress($item->id)
        ]);
    }
} 