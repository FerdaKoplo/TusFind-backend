<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemLost;
use Illuminate\Http\Request;

class ItemLostController extends Controller
{
    public function index()
    {
        $items = ItemLost::with(['item', 'category', 'images'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'item_id' => 'required|exists:items,id',
            'lost_date' => 'nullable|date',
            'lost_location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $lostItem = ItemLost::create([
            'user_id' => $request->user()->id,
            'category_id' => $validated['category_id'],
            'item_id' => $validated['item_id'],
            'lost_date' => $validated['lost_date'] ?? null,
            'lost_location' => $validated['lost_location'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => ItemLost::STATUS_PENDING,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lost item reported',
            'data' => $lostItem
        ], 201);
    }

    public function show($id)
    {
        $item = ItemLost::with(['item', 'category', 'images'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $lostItem = ItemLost::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'item_id' => 'sometimes|exists:items,id',
            'lost_date' => 'nullable|date',
            'lost_location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $lostItem->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lost item updated',
            'data' => $lostItem
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $lostItem = ItemLost::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $lostItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lost item deleted'
        ]);
    }
}
