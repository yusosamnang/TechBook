<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function indexBook(Request $req)
    {
        $books = Book::all();

        if ($books->isEmpty()) {
            return view('admin.book')->with("books", null);
        }

        return view('admin.book')->with("books", $books);
    }
    public function pendingBooks(Request $request)
    {
        $pendingBooks = Book::where('status', 'Pending')->get();

        return view('admin.pendingBooks', compact('pendingBooks'));
    }

    public function addBook(Request $req)
    {
        $categories = Category::all();
        return view('admin.addBook', ['categories' => $categories]);
    }

    public function addNewBook(Request $req)
{
    // Validate the incoming request
    $req->validate([
        'ISBN' => 'required|string|max:255|unique:books,ISBN',
        'title' => 'required|string|max:255',
        'category' => 'required|exists:categories,id',
        'author_name' => 'required|string|max:255',
        'published_date' => 'required|date',
        'price' => 'required|numeric',
        'status' => 'required|string|in:Approved,Denied,Pending',
        'type' => 'required|string|in:Free,Paid',
        'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'book_file' => 'required|mimes:pdf|max:2048',
    ]);

    try {
        // Handle adding a new book
        $book = new Book();
        $book->ISBN = $req->ISBN;
        $book->title = $req->title;
        $book->category_id = $req->category;
        $book->author_name = $req->author_name;
        $book->published_date = $req->published_date;
        $book->price = $req->price;
        $book->status = $req->status;
        $book->type = $req->type;
        $book->user_id = Auth::id(); // Set user_id from the currently authenticated user

        // Upload cover image to S3
        $coverImage = $req->file('cover_image');
        $coverImageName = time() . '_' . $coverImage->getClientOriginalName();
        $coverImagePath = 'uploads/cover_images/' . $coverImageName;
        Storage::disk('s3')->put($coverImagePath, file_get_contents($coverImage));
        $book->cover_url = Storage::disk('s3')->url($coverImagePath);

        // Upload book file to S3
        $bookFile = $req->file('book_file');
        $bookFileName = time() . '_' . $bookFile->getClientOriginalName();
        $bookFilePath = 'uploads/book_files/' . $bookFileName;
        Storage::disk('s3')->put($bookFilePath, file_get_contents($bookFile));
        $book->book_url = Storage::disk('s3')->url($bookFilePath);

        // Save the book
        $book->save();

        return redirect()->back()->with('success', 'Book added successfully');
    } catch (\Exception $e) {
        // Log the error
        \Log::error('Error adding new book: ' . $e->getMessage());
        
        // Return back with error message
        return redirect()->back()->with('error', 'Failed to add new book');
    }
}


    public function editBook($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('admin.editBook', compact('book', 'categories'));
    }

    public function updateBook(Request $req, $id)
    {
        // Validate the incoming request
        $req->validate([
            'ISBN' => 'required|string|max:255|unique:books,ISBN,' . $id,
            'title' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'author_name' => 'required|string|max:255',
            'published_date' => 'required|date',
            'price' => 'required|numeric',
            'status' => 'required|string|in:Approved,Denied,Pending',
            'type' => 'required|string|in:Free,Paid',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'book_file' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Retrieve the book by its ID
        $book = Book::findOrFail($id);

        // Update the book information
        $book->ISBN = $req->ISBN;
        $book->title = $req->title;
        $book->category_id = $req->category;
        $book->author_name = $req->author_name;
        $book->published_date = $req->published_date;
        $book->price = $req->price;
        $book->status = $req->status;
        $book->type = $req->type;

        // Handle cover image upload if provided
        if ($req->hasFile('cover_image')) {
            $coverImage = $req->file('cover_image');
            $coverImageName = time() . '_' . $coverImage->getClientOriginalName();
            $coverImagePath = 'uploads/cover_images/' . $coverImageName;
            Storage::disk('s3')->put($coverImagePath, file_get_contents($coverImage));
            $book->cover_url = Storage::disk('s3')->url($coverImagePath);
        }

        // Handle book file upload if provided
        if ($req->hasFile('book_file')) {
            $bookFile = $req->file('book_file');
            $bookFileName = time() . '_' . $bookFile->getClientOriginalName();
            $bookFilePath = 'uploads/book_files/' . $bookFileName;
            Storage::disk('s3')->put($bookFilePath, file_get_contents($bookFile));
            $book->book_url = Storage::disk('s3')->url($bookFilePath);
        }

        // Save the updated book
        $book->save();

        return redirect()->back()->with('success', 'Book updated successfully');
    }

    public function deleteBook(Request $req)
    {
        $book = Book::find($req->id);
        if ($book) {
            $book->delete();
            return redirect()->back()->with('success', 'Book deleted successfully');
        }
        return redirect()->back()->with('error', 'Book not found');
    }
    public function showDetail(Book $book)
    {
        $book->load('feedbacks.user');
        return view('detailBook', compact('book'));
    }
    // User-specific methods
    public function index()
    {
        $books = Book::all();
        return view('detailBook', compact('books'));
    }

    public function viewPDF(Request $request)
    {
        $pdfUrl = $request->query('url');
        return view('pdfViewer', ['pdfUrl' => $pdfUrl]);
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Approved,Denied,Pending',
        ]);

        $book = Book::findOrFail($id);
        $book->status = $request->status;
        $book->save();

        return redirect()->back()->with('success', 'Status updated successfully');
    }

}
