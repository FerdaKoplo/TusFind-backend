<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemFound;
use App\Models\ItemLost;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $lostCount = ItemLost::count();
        $foundCount = ItemFound::count();
        $totalReports = $lostCount + $foundCount;

        $resolvedLost = ItemLost::where('status', ItemLost::STATUS_RESOLVED)->count();
        $resolvedFound = ItemFound::where('status', ItemFound::STATUS_CLAIMED)->count();
        $resolvedCount = $resolvedLost + $resolvedFound;

        $recentLost = ItemLost::with('item') 
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item ? $item->item->name : 'Unknown Item',
                    'brand' => $item->item ? $item->item->brand : '',
                    'color' => '', 
                    'type' => 'lost', 
                    'created_at' => $item->created_at
                ];
            });

        $recentFound = ItemFound::with('item')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->item ? $item->item->name : 'Unknown Item',
                    'brand' => $item->item ? $item->item->brand : '',
                    'color' => '',
                    'type' => 'found', 
                    'created_at' => $item->created_at
                ];
            });

        $recentActivities = $recentLost->merge($recentFound)
            ->sortByDesc('created_at')
            ->take(5)
            ->values(); 

        return response()->json([
            'totalReports' => $totalReports,
            'lostCount' => $lostCount,
            'foundCount' => $foundCount,
            'resolvedCount' => $resolvedCount,
            'recentActivities' => $recentActivities
        ]);
    }
}
