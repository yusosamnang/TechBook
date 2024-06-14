<?php

namespace App\Http\Controllers;

use App\Models\BookRequest;
use App\Models\Feedback;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Counting New Book Requests
        $totalNewBookRequests = BookRequest::count();
        $pendingNewBookRequests = BookRequest::where('status', 'pending')->count();

        // Counting Feedbacks
        $totalFeedbacks = Feedback::count();
        $pendingFeedbacks = Feedback::where('status', 'pending')->count();

        // Counting Total Books
        $totalBooks = Book::count();

        // Counting Total Categories
        $totalCategories = Category::count();

        // Counting Total Users
        $totalUsers = User::count();

        return view('admin.dashboard', compact(
            'totalNewBookRequests',
            'pendingNewBookRequests',
            'totalFeedbacks',
            'pendingFeedbacks',
            'totalBooks',
            'totalCategories',
            'totalUsers'
        ));
    }
}
