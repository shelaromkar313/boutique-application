<?php

namespace App\Http\Controllers;

use App\Services\TryOnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TryOnController extends Controller
{
    protected TryOnService $tryOnService;

    public function __construct(TryOnService $tryOnService)
    {
        $this->tryOnService = $tryOnService;
    }

    /**
     * Get available demo models
     */
    public function getDemoModels(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'models' => $this->tryOnService->getDemoModels(),
        ]);
    }

    /**
     * Process a virtual try-on request
     */
    public function tryOn(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'person_image' => 'required|string', // base64 string or url
            'garment_image' => 'required|string',
            'category' => 'nullable|string|in:upper_body,lower_body,dresses',
            'product_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $personImage = $request->input('person_image');
        $garmentImage = $request->input('garment_image');
        $category = $request->input('category', 'upper_body');
        $productName = $request->input('product_name');

        // If personImage is a remote URL, convert it to base64 via HTTP with proper headers
        if (filter_var($personImage, FILTER_VALIDATE_URL)) {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => implode("\r\n", [
                        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/125.0 Safari/537.36',
                        'Accept: image/webp,image/apng,image/*,*/*',
                        'Referer: https://images.unsplash.com/',
                    ]),
                    'timeout' => 15,
                ],
            ]);
            $imgData = @file_get_contents($personImage, false, $context);
            if ($imgData && strlen($imgData) > 1000) {
                $personImage = 'data:image/jpeg;base64,' . base64_encode($imgData);
            } else {
                Log::warning('TryOnController: Failed to fetch person image URL', [
                    'url' => substr($personImage, 0, 100),
                    'bytes' => $imgData ? strlen($imgData) : 0,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Could not load the selected model photo. Please try the Upload Photo tab instead.',
                ], 422);
            }
        }

        // Validate that personImage is now a proper base64 data URI
        if (!str_starts_with($personImage, 'data:image/')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid person image format. Please upload a photo directly.',
            ], 422);
        }

        $result = $this->tryOnService->processTryOn(
            $personImage,
            $garmentImage,
            $category,
            $productName
        );

        return response()->json($result);
    }
}
