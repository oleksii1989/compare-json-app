<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CompareJsonController extends Controller
{
    // Store first payload in cache
    public function storePayload1(Request $request)
    {
        $payload = $request->json()->all();
        Cache::put('payload1', $payload, now()->addMinutes(20));
        return response()->json(['message' => 'Payload 1 received and stored']);
    }

    // Store second payload in cache
    public function storePayload2(Request $request)
    {
        $payload = $request->json()->all();
        Cache::put('payload2', $payload, now()->addMinutes(20));
        return response()->json(['message' => 'Payload 2 received and stored']);
    }

    // Compare payloads and return diff
    public function getDiff()
    {
        $payload1 = Cache::get('payload1');
        $payload2 = Cache::get('payload2');

        if (!$payload1 || !$payload2) {
            return response()->json(['error' => 'Both payloads must be sent first'], 400);
        }

        $diff = $this->comparePayloads($payload1, $payload2);
        return response()->json(['diff' => $diff]);
    }


    // Compare two payloads and return structured diff
    private function comparePayloads($p1, $p2)
    {
        $diff = [];

        // Compare top-level fields
        foreach (['title', 'description'] as $field) {
            if ($p1[$field] !== $p2[$field]) {
                $diff[$field] = [
                    'from' => $p1[$field],
                    'to' => $p2[$field]
                ];
            }
        }

        // Compare images
        $diff['images'] = $this->compareImages($p1['images'], $p2['images']);

        // Compare variants
        $diff['variants'] = $this->compareVariants($p1['variants'], $p2['variants']);

        return $diff;
    }


    // Compare images arrays
    private function compareImages($images1, $images2)
    {
        $result = [
            'added' => [],
            'removed' => [],
            'changed' => []
        ];

        $map1 = collect($images1)->keyBy('id');
        $map2 = collect($images2)->keyBy('id');

        // Find removed and changed
        foreach ($map1 as $id => $img1) {
            if (!$map2->has($id)) {
                $result['removed'][] = $img1;
            } else {
                $img2 = $map2[$id];
                $changes = [];
                foreach (['url', 'position'] as $field) {
                    if ($img1[$field] !== $img2[$field]) {
                        $changes[$field] = [
                            'from' => $img1[$field],
                            'to' => $img2[$field]
                        ];
                    }
                }
                if ($changes) {
                    $result['changed'][] = [
                        'id' => $id,
                        'changes' => $changes
                    ];
                }
            }
        }

        // Find added
        foreach ($map2 as $id => $img2) {
            if (!$map1->has($id)) {
                $result['added'][] = $img2;
            }
        }

        return $result;
    }


    // Compare variants arrays
    private function compareVariants($variants1, $variants2)
    {
        $result = [
            'added' => [],
            'removed' => [],
            'changed' => []
        ];

        $map1 = collect($variants1)->keyBy('id');
        $map2 = collect($variants2)->keyBy('id');

        // Find removed and changed
        foreach ($map1 as $id => $v1) {
            if (!$map2->has($id)) {
                $result['removed'][] = $v1;
            } else {
                $v2 = $map2[$id];
                $changes = [];
                foreach (['sku', 'barcode', 'image_id', 'inventory_quantity'] as $field) {
                    if ($v1[$field] !== $v2[$field]) {
                        $changes[$field] = [
                            'from' => $v1[$field],
                            'to' => $v2[$field]
                        ];
                    }
                }
                if ($changes) {
                    $result['changed'][] = [
                        'id' => $id,
                        'changes' => $changes
                    ];
                }
            }
        }

        // Find added
        foreach ($map2 as $id => $v2) {
            if (!$map1->has($id)) {
                $result['added'][] = $v2;
            }
        }

        return $result;
    }
}
