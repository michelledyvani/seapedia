<?php
namespace App\Http\Controllers;

use App\Models\AppReview;
use Illuminate\Http\Request;

class AppReviewController extends Controller
{
    public function index()
    {
        $reviews = AppReview::latest()->take(30)->get();
        return view('public.reviews', compact('reviews'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:100',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|max:1000',
        ]);

        AppReview::create([
            'reviewer_name' => strip_tags($validated['reviewer_name']),
            'rating'        => $validated['rating'],
            'comment'       => strip_tags($validated['comment']),
        ]);

        return redirect()->route('reviews.index')->with('success', 'Terima kasih atas ulasanmu!');
    }
}
