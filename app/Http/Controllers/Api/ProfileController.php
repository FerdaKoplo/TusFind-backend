<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchReport;
use Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // ivan
    public function stats(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'lost_count' => $user->itemsLost()->count(),
                'found_count' => $user->itemsFound()->count(),
                'resolved_count' => $user->itemsLost()->where('status', 'resolved')->count()
                    + $user->itemsFound()->where('status', 'claimed')->count(),
            ]
        ]);
    }

    // ivan
    public function show(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }


    // public function update(Request $request)
    // {
    //     $user = $request->user();

    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'phone' => 'nullable|string|max:20', 
    //     ]);

    //     $user->update($request->only(['name', 'phone']));

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Profile updated successfully',
    //         'data' => $user
    //     ]);
    // }

    // ivan
    public function myLostItems(Request $request)
    {
        $query = $request->user()->itemsLost()->with(['item', 'category']);

        $query->when($request->status, function ($q, $status) {
            return $q->where('status', $status);
        });

        $query->when($request->category_id, function ($q, $catId) {
            return $q->where('category_id', $catId);
        });

        $query->when($request->search, function ($q, $search) {
            return $q->where(function ($subQ) use ($search) {
                $subQ->where('description', 'like', "%{$search}%")
                    ->orWhereHas('item', function ($itemQ) use ($search) {
                        $itemQ->where('name', 'like', "%{$search}%");
                    });
            });
        });

        $items = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $items
        ]);
    }

    // public function changePassword(Request $request)
    // {
    //     $request->validate([
    //         'current_password' => 'required',
    //         'new_password' => 'required|min:8|confirmed',
    //     ]);

    //     $user = $request->user();

    //     if (!Hash::check($request->current_password, $user->password)) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Current password does not match'
    //         ], 422);
    //     }

    //     $user->update([
    //         'password' => Hash::make($request->new_password)
    //     ]);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Password updated successfully'
    //     ]);
    // }

    // ivan
    public function myFoundItems(Request $request)
    {
        $query = $request->user()->itemsFound()->with(['item', 'category']);

        $query->when($request->status, function ($q, $status) {
            return $q->where('status', $status);
        });

        $query->when($request->category_id, function ($q, $catId) {
            return $q->where('category_id', $catId);
        });

        $query->when($request->search, function ($q, $search) {
            return $q->where(function ($subQ) use ($search) {
                $subQ->where('description', 'like', "%{$search}%")
                    ->orWhereHas('item', function ($itemQ) use ($search) {
                        $itemQ->where('name', 'like', "%{$search}%");
                    });
            });
        });

        $items = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $items
        ]);
    }

    // ivan
    public function myMatches(Request $request)
    {
        $userId = $request->user()->id;

        $matches = MatchReport::with(['itemLost.item', 'itemFound.item'])
            ->whereHas('itemLost', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->has('itemFound')
            ->when($request->status, function ($q, $status) {
                return $q->where('status', $status);
            })
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $matches
        ]);
    }
}
