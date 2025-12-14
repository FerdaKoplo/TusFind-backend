<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemFound;
use Illuminate\Http\Request;

class ItemFoundController extends Controller
{
    public function index()
    {
        $items = ItemFound::with(['item', 'category', 'images'])
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
            'found_date' => 'nullable|date',
            'found_location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $foundItem = ItemFound::create([
            'user_id' => $request->user()->id,
            'category_id' => $validated['category_id'],
            'item_id' => $validated['item_id'],
            'found_date' => $validated['found_date'] ?? null,
            'found_location' => $validated['found_location'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => ItemFound::STATUS_PENDING,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Found item reported',
            'data' => $foundItem
        ], 201);
    }

    public function show($id)
    {
        $item = ItemFound::with(['item', 'category', 'images'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $foundItem = ItemFound::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|exists:categories,id',
            'item_id' => 'sometimes|exists:items,id',
            'found_date' => 'nullable|date',
            'found_location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $foundItem->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Found item updated',
            'data' => $foundItem
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $foundItem = ItemFound::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $foundItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Found item deleted'
        ]);
    }
}
