<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Item::with('category')->get()
        ]);
    }

    public function show(Item $item)
    {
        return response()->json([
            'data' => $item->load('category')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'brand' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $item = Item::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand' => $request->brand,
            'color' => $request->color,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Item created successfully',
            'data' => $item->load('category')
        ], 201);
    }
}
