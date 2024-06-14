<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function add(Request $request, $id)
    {
        $user = auth()->user();
        $book = Book::find($id);

        if (!$book) {
            return redirect()->back()->with('error', 'Book not found.');
        }

        $user->favorites()->syncWithoutDetaching([$id]);

        return redirect()->back()->with('success', 'Book added to favorites successfully');
    }

    public function remove(Request $request, $id)
    {
        $user = auth()->user();
        $user->favorites()->detach($id);

        return redirect()->back()->with('success', 'Book removed from favorites successfully');
    }
    public function index()
    {
        $favorites = auth()->user()->favorites()->with('users')->get();

        return view('favorites.index', ['favorites' => $favorites]);
    }
}

