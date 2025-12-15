<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
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
            'item_id' => 'nullable|exists:items,id',
            'custom_item_name' => 'nullable|string|max:255',
            'lost_date' => 'nullable|date',
            'lost_location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if (!$request->item_id && !$request->custom_item_name) {
            return response()->json([
                'success' => false,
                'message' => 'Item is required'
            ], 422);
        }

        $itemId = $this->resolveItemId($request);


        $lostItem = ItemLost::create([
            'user_id' => $request->user()->id,
            'category_id' => $validated['category_id'],
            'item_id' => $itemId,
            'lost_date' => $validated['lost_date'] ?? null,
            'lost_location' => $validated['lost_location'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => ItemLost::STATUS_PENDING,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lost item reported',
            'data' => $lostItem->load(['item', 'category', 'images']),
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

        if (!$request->item_id && !$request->custom_item_name) {
            return response()->json([
                'success' => false,
                'message' => 'Item is required'
            ], 422);
        }

        $itemId = $request->item_id
            ? $request->item_id
            : ($request->custom_item_name
                ? $this->resolveItemId($request)
                : $lostItem->item_id);

        $lostItem->update([
            'category_id' => $validated['category_id'] ?? $lostItem->category_id,
            'item_id' => $itemId,
            'lost_date' => $validated['lost_date'] ?? $lostItem->lost_date,
            'lost_location' => $validated['lost_location'] ?? $lostItem->lost_location,
            'description' => $validated['description'] ?? $lostItem->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lost item updated',
            'data' => $lostItem->load(['item', 'category', 'images']),
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

    private function resolveItemId(Request $request): int
    {
        if ($request->item_id) {
            return $request->item_id;
        }

        return Item::firstOrCreate(
            [
                'name' => trim($request->custom_item_name),
                'category_id' => $request->category_id,
            ]
        )->id;
    }
}
