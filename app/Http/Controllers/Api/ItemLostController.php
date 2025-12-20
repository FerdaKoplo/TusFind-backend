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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'item_id' => 'nullable|exists:items,id',
            'custom_item_name' => 'nullable|string|max:255',
            'lost_date' => 'nullable|date',
            'lost_location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if (!$request->filled('item_id') && !$request->filled('custom_item_name')) {
            return response()->json([
                'success' => false,
                'message' => 'Item is required'
            ], 422);
        }

        $data = [
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'lost_date' => $request->lost_date,
            'lost_location' => $request->lost_location,
            'description' => $request->description,
            'status' => ItemLost::STATUS_PENDING,
        ];
        if ($request->filled('item_id')) {
            $data['item_id'] = $request->item_id;
            $data['custom_item_name'] = null;
        } else {
            $data['item_id'] = null;
            $data['custom_item_name'] = $request->custom_item_name;
        }

        $lostItem = ItemLost::create($data);

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

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'item_id' => 'nullable|exists:items,id',
            'custom_item_name' => 'nullable|string',
            'lost_location' => 'required|string',
            'description' => 'nullable|string',
            'lost_date' => 'nullable|date',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'lost_location' => $request->lost_location,
            'description' => $request->description,
            'lost_date' => $request->lost_date,
        ];

        if ($request->filled('item_id')) {
            $data['item_id'] = $request->item_id;
            $data['custom_item_name'] = null;
        } elseif ($request->filled('custom_item_name')) {
            $data['custom_item_name'] = $request->custom_item_name;
            $data['item_id'] = null;
        }

        $lostItem->update($data);

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

    // private function resolveItemId(Request $request): int
    // {
    //     if ($request->item_id) {
    //         return $request->item_id;
    //     }

    //     return Item::firstOrCreate(
    //         [
    //             'name' => trim($request->custom_item_name),
    //             'category_id' => $request->category_id,
    //         ]
    //     )->id;
    // }
}
