<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::all();
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function create($bookId)
    {
        $book = Book::find($bookId); // Correct variable name
        return view('detailBook', compact('book')); // Pass single book, not collection
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'content' => 'required|string',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'reviewer_id' => null, // Default value as reviewer_id is nullable
            'content' => $request->content,
            'status' => 'pending',
        ]);

        return redirect()->route('books.showDetail', $request->book_id)->with('success', 'Feedback submitted successfully.');
    }

    public function approve($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->status = 'approved';
        $feedback->reviewer_id = Auth::id();
        $feedback->save();

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback approved successfully.');
    }

    public function deny($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->status = 'denied';
        $feedback->reviewer_id = Auth::id();
        $feedback->save();

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback denied successfully.');
    }
}
