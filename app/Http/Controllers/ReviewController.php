<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Get approved reviews for a specific product
     */
    public function index($identifier)
    {
        $product = Product::where('est_id', $identifier)
            ->orWhere('id', $identifier)
            ->first();

        $estId = $product ? $product->est_id : $identifier;

        $reviews = Review::where('product_est_id', $estId)
            ->where('is_approved', true)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'avg_rating' => $reviews->avg('rating') ? round($reviews->avg('rating'), 1) : 5.0,
            'total' => $reviews->count(),
        ]);
    }

    /**
     * Submit a customer rating and review (1 to 5 stars max)
     */
    public function store(Request $request, $identifier)
    {
        $validator = Validator::make($request->all(), [
            'rating'    => 'required|integer|min:1|max:5',
            'user_name' => 'required|string|max:255',
            'comment'   => 'required|string|min:3|max:2000',
        ], [
            'rating.required'    => 'Please select a star rating between 1 and 5 stars.',
            'rating.min'         => 'Rating must be at least 1 star.',
            'rating.max'         => 'Rating cannot exceed 5 stars.',
            'user_name.required' => 'Please enter your name.',
            'comment.required'   => 'Please write your review feedback.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $product = Product::where('est_id', $identifier)
            ->orWhere('id', $identifier)
            ->first();

        $estId = $product ? $product->est_id : $identifier;
        $userId = Auth::id() ?? null;
        $userName = $request->input('user_name');

        if (Auth::check() && empty($userName)) {
            $userName = Auth::user()->name;
        }

        $review = Review::create([
            'product_est_id' => $estId,
            'user_id'        => $userId,
            'user_name'      => $userName,
            'rating'         => (int) $request->input('rating'),
            'comment'        => trim($request->input('comment')),
            'is_approved'    => true, // Approved and live
        ]);

        // Recalculate and update product's overall rating & review count
        if ($product && method_exists($product, 'updateRatingStats')) {
            $product->updateRatingStats();
        }

        $successMsg = "✨ Thank you, {$userName}! Your {$review->rating}-Star review has been published.";

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
                'review'  => $review,
            ], 201);
        }

        return back()->with('success', $successMsg);
    }
}
