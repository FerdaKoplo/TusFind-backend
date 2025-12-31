<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemFound;
use App\Models\ItemLost;
use App\Models\MatchReport;
use Illuminate\Http\Request;

class MatchReportController extends Controller
{
    public function index()
    {
        $matches = MatchReport::with([
            'itemLost.item',
            'itemFound.item'
        ])
            ->has('itemLost')
            ->has('itemFound')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $matches
        ]);
    }

    public function show($id)
    {
        $match = MatchReport::with([
            'itemLost.item',
            'itemFound.item'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $match
        ]);
    }

    public function autoMatch()
    {
        $lostItems = ItemLost::where('status', ItemLost::STATUS_PENDING)->get();
        $foundItems = ItemFound::where('status', ItemFound::STATUS_PENDING)->get();

        $createdMatches = [];

        foreach ($lostItems as $lost) {
            foreach ($foundItems as $found) {

                $score = $this->calculateMatchScore($lost, $found);

                if ($score >= 70) {
                    $match = MatchReport::firstOrCreate(
                        [
                            'item_lost_id' => $lost->id,
                            'item_found_id' => $found->id,
                        ],
                        [
                            'match_score' => $score,
                            'status' => 'pending',
                        ]
                    );

                    $createdMatches[] = $match;
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Auto matching completed',
            'matches_created' => count($createdMatches),
            'data' => $createdMatches
        ]);
    }

    public function confirm($id)
    {
        $match = MatchReport::findOrFail($id);

        $match->update([
            'status' => 'confirmed'
        ]);

        $match->itemLost->update(['status' => 'matched']);
        $match->itemFound->update(['status' => 'claimed']);

        return response()->json([
            'success' => true,
            'message' => 'Match confirmed'
        ]);
    }

    public function reject($id)
    {
        $match = MatchReport::findOrFail($id);

        $match->update([
            'status' => 'rejected'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Match rejected'
        ]);
    }

    private function calculateMatchScore(ItemLost $lost, ItemFound $found)
    {
        $score = 0;

        if ($lost->category_id === $found->category_id) {
            $score += 40;
        }

        similar_text(
            strtolower($lost->item->name),
            strtolower($found->item->name),
            $percent
        );

        $score += $percent * 0.6;

        return min(100, (int) $score);
    }
}
