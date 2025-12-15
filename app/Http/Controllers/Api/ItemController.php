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
}
