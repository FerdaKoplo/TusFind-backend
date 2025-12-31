<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
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
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'item_id' => 'nullable|exists:items,id',
            'custom_item_name' => 'nullable|string|max:255',
            'found_date' => 'nullable|date',
            'found_location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if (!$request->filled('item_id') && !$request->filled('custom_item_name')) {
            return response()->json([
                'success' => false,
                'message' => 'Item is required (select from list or type custom name)'
            ], 422);
        }

        $data = [
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'found_date' => $request->found_date,
            'found_location' => $request->found_location,
            'description' => $request->description,
            'status' => ItemFound::STATUS_PENDING,
        ];

        if ($request->filled('item_id')) {
            $data['item_id'] = $request->item_id;
            $data['custom_item_name'] = null;
        } else {
            $data['item_id'] = null;
            $data['custom_item_name'] = $request->custom_item_name;
        }

        $foundItem = ItemFound::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Found item reported',
            'data' => $foundItem->load(['item', 'category', 'images']),
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

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'item_id' => 'nullable|exists:items,id',
            'custom_item_name' => 'nullable|string|max:255',
            'found_date' => 'nullable|date',
            'found_location' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'found_date' => $request->found_date,
            'found_location' => $request->found_location,
            'description' => $request->description,
        ];

        if ($request->filled('item_id')) {
            $data['item_id'] = $request->item_id;
            $data['custom_item_name'] = null;
        } elseif ($request->filled('custom_item_name')) {
            $data['custom_item_name'] = $request->custom_item_name;
            $data['item_id'] = null;
        }

        $foundItem->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Found item updated',
            'data' => $foundItem->load(['item', 'category', 'images']),
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
