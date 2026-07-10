<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeedbackController extends Controller
{
    /**
     * Tampilkan form rating setelah booking selesai
     */
    public function create(string $bookingId)
    {
        $booking = Booking::with('table')
            ->where('user_id', auth()->id())
            ->where('status', 'Completed')
            ->findOrFail($bookingId);

        if ($booking->hasFeedback()) {
            return redirect()->route('booking.history')->with('info', 'Anda sudah memberikan feedback untuk booking ini.');
        }

        return view('customer.feedback.create', compact('booking'));
    }

    /**
     * Simpan rating & feedback
     */
    public function store(Request $request, string $bookingId)
    {
        $booking = Booking::where('user_id', auth()->id())
            ->where('status', 'Completed')
            ->findOrFail($bookingId);

        if ($booking->hasFeedback()) {
            return back()->withErrors(['error' => 'Anda sudah memberikan feedback.']);
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        Feedback::create([
            'id'         => 'FB' . strtoupper(Str::random(9)),
            'booking_id' => $bookingId,
            'user_id'    => auth()->id(),
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'],
        ]);

        return redirect()->route('dashboard')->with('success', 'Terima kasih atas feedback Anda! ⭐');
    }

    /**
     * Owner: Lihat semua feedback
     */
    public function ownerIndex()
    {
        $feedbacks = Feedback::with(['user', 'booking.table'])
            ->latest()->paginate(15);

        $avgRating = Feedback::avg('rating');
        $ratingDistribution = Feedback::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')->orderBy('rating', 'desc')->pluck('count', 'rating');

        return view('owner.feedbacks.index', compact('feedbacks', 'avgRating', 'ratingDistribution'));
    }
}
