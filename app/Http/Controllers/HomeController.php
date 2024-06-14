<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $booksQuery = Book::query();

        // Check if there is a search query
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $booksQuery->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('author_name', 'like', '%' . $searchTerm . '%');
        }

        // Fetch books with pagination
        $books = $booksQuery->paginate(12);

        // Fetch all categories for dropdown menu
        $categories = Category::all();

        // Return view with books and categories
        return view('dashboard', compact('books', 'categories'));
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        $books = Book::where('title', 'like', "%{$query}%")
            ->orWhere('author_name', 'like', "%{$query}%")
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->get();

        return view('partials.book_results', compact('books'))->render();
    }

    public function booksByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $books = Book::where('category_id', $categoryId)->paginate(12);
        $categories = Category::all();

        return view('dashboard', compact('books', 'categories', 'category'));
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }
    
}


