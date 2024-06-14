<?php

namespace App\Http\Controllers;

use App\Models\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BookRequestStatus;
use Illuminate\Support\Facades\Notification;

class BookRequestController extends Controller
{
    public function index()
    {
        $requests = BookRequest::where('user_id', auth()->id())->get();
        return view('request_books', compact('requests'));
    }
    
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_title' => 'sometimes|required|max:255',
            'author_name' => 'sometimes|required|max:255',
            'publish_date' => 'sometimes|required|date',
            'url' => 'sometimes|required|url',
            'reason' => 'required',
        ]);
        
        BookRequest::create([
            'user_id' => Auth::id(),
            'book_title' => $request->book_title,
            'reviewer_id' => null, // Default value as reviewer_id is nullable
            'author_name' => $request->author_name,
            'status' => 'pending',
            'publish_date' => $request->publish_date,
            'url' => $request->url,
            'reason' => $request->reason,
        ]);
        
        return back()->with('success', 'Request submitted successfully.');
    }

    public function edit($id)
    {
        $request = BookRequest::findOrFail($id);
        return view('edit_request_books', compact('request'));
    }
    

    public function update(Request $request, BookRequest $bookRequest)
    {
        $validated = $request->validate([
            'book_title' => 'required_without:url|max:255',
            'author_name' => 'required_without:url|max:255',
            'publish_date' => 'required_without:url|date',
            'url' => 'nullable|url',
            'reason' => 'required',
        ]);



        $bookRequest->update($validated);

        return back()->with('success', 'Request updated successfully.');
    }

    public function destroy(BookRequest $bookRequest)
    {
        $bookRequest->delete();
        return back()->with('success', 'Request deleted successfully.');
    }

    public function admin_index()
    {
        if (Auth::user()->userType == 'admin') {
            $requests = BookRequest::with('user')->get();
            
            return view('admin.admin_request_books', compact('requests'));
        }
    }
    public function approve($id)
    {
        $feedback = BookRequest::findOrFail($id);
        $feedback->status = 'approved';
        $feedback->reviewer_id = Auth::id();
        $feedback->save();

        // Send notification
        $feedback->user->notify(new BookRequestStatus($feedback, 'approved'));

        return redirect()->route('admin.book_request')->with('success', 'Request approved successfully.');
    }

    public function deny($id)
    {
        $feedback = BookRequest::findOrFail($id);
        $feedback->status = 'denied';
        $feedback->reviewer_id = Auth::id();
        $feedback->save();

        // Send notification
        $feedback->user->notify(new BookRequestStatus($feedback, 'denied'));

        return redirect()->route('admin.book_request')->with('success', 'Request denied successfully.');
    }


}