<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks =
            Feedback::all();

        return view(
            'feedback.index',
            compact('feedbacks')
        );
    }

    public function store(Request $request)
    {
        Feedback::create([
            'customer_name' =>
                $request->customer_name,

            'message' =>
                $request->message
        ]);

        return redirect('/feedback');
    }
}