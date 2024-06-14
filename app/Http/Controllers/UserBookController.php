<?php

// UserBookController.php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserBookController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $books = Book::where('user_id', $user_id)
                     ->get();

        return view('books.index', compact('books'));
    }
    // UserBookController.php
    public function create(Request $req)
{
    // Load categories for the dropdown
    $categories = Category::all();
    
    return view('books.create', ['categories' => $categories]);
}


public function store(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'ISBN' => 'required|string|max:255|unique:books,ISBN',
        'title' => 'required|string|max:255',
        'category' => 'required|exists:categories,id',
        'author_name' => 'required|string|max:255',
        'published_date' => 'required|date',
        'price' => 'required|numeric',
        'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'book_file' => 'required|mimes:pdf|max:2048',
    ]);

    try {
        // Handle adding a new book
        $book = new Book();
        $book->ISBN = $request->ISBN;
        $book->title = $request->title;
        $book->category_id = $request->category;
        $book->author_name = $request->author_name;
        $book->published_date = $request->published_date;
        $book->price = $request->price;
        $book->status = 'Pending'; // Default status is Pending
        $book->type = $request->price > 0 ? 'Paid' : 'Free'; // Set type based on price
        $book->user_id = Auth::id(); // Set user_id from the currently authenticated user

        // Upload cover image
        $coverImage = $request->file('cover_image');
        $coverImageName = time() . '_' . $coverImage->getClientOriginalName();
        $coverImagePath = 'uploads/cover_images/' . $coverImageName;
        Storage::disk('s3')->put($coverImagePath, file_get_contents($coverImage));
        $book->cover_url = Storage::url($coverImagePath);

        // Upload book file
        $bookFile = $request->file('book_file');
        $bookFileName = time() . '_' . $bookFile->getClientOriginalName();
        $bookFilePath = 'uploads/book_files/' . $bookFileName;
        Storage::disk('s3')->put($bookFilePath, file_get_contents($bookFile));
        $book->book_url = Storage::url($bookFilePath);

        // Save the book
        $book->save();

        return redirect()->route('user.books.index')->with('success', 'Book added successfully');
    } catch (\Exception $e) {
        // Log the error
        \Log::error('Error adding new book: ' . $e->getMessage());
        
        // Return back with error message
        return redirect()->back()->with('error', 'Failed to add new book');
    }
}
    // UserBookController.php

public function edit(Book $book)
{
    if ($book->user_id != Auth::id() || $book->status !== 'Pending') {
        return redirect()->route('user.books.index')->with('error', 'You cannot edit this book.');
    }
    $categories = Category::all();
    return view('books.edit', compact('book', 'categories'));
}

public function update(Request $request, Book $book)
{
    // Validate the incoming request
    $request->validate([
        'ISBN' => 'required|string|max:255|unique:books,ISBN,' . $book->id,
        'title' => 'required|string|max:255',
        'category' => 'required|exists:categories,id',
        'author_name' => 'required|string|max:255',
        'published_date' => 'required|date',
        'price' => 'required|numeric',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'book_file' => 'nullable|mimes:pdf|max:2048',
    ]);

    try {
        // Handle updating the book
        $book->ISBN = $request->ISBN;
        $book->title = $request->title;
        $book->category_id = $request->category;
        $book->author_name = $request->author_name;
        $book->published_date = $request->published_date;
        $book->price = $request->price;
        $book->type = $request->price > 0 ? 'Paid' : 'Free'; // Update type based on price

        // Update cover image if provided
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImageName = time() . '_' . $coverImage->getClientOriginalName();
            $coverImagePath = 'uploads/cover_images/' . $coverImageName;
            Storage::disk('s3')->put($coverImagePath, file_get_contents($coverImage));
            $book->cover_url = Storage::url($coverImagePath);
        }

        // Update book file if provided
        if ($request->hasFile('book_file')) {
            $bookFile = $request->file('book_file');
            $bookFileName = time() . '_' . $bookFile->getClientOriginalName();
            $bookFilePath = 'uploads/book_files/' . $bookFileName;
            Storage::disk('s3')->put($bookFilePath, file_get_contents($bookFile));
            $book->book_url = Storage::url($bookFilePath);
        }

        // Save the updated book
        $book->save();

        return redirect()->route('user.books.index')->with('success', 'Book updated successfully');
    } catch (\Exception $e) {
        // Log the error
        \Log::error('Error updating book: ' . $e->getMessage());
        
        // Return back with error message
        return redirect()->back()->with('error', 'Failed to update book');
    }
}

public function destroy(Book $book)
{
    if ($book->user_id == Auth::id() && $book->status === 'Pending') {
        try {
            // Delete book
            $book->delete();

            return redirect()->route('user.books.index')->with('success', 'Book deleted successfully');
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error deleting book: ' . $e->getMessage());
            
            // Return back with error message
            return redirect()->back()->with('error', 'Failed to delete book');
        }
    }

    return redirect()->route('user.books.index')->with('error', 'You cannot delete this book.');
}


    // Other methods like create, store, edit, update, destroy will be implemented here
}

