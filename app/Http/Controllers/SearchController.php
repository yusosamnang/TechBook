<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\View\Components\SearchResults;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Perform the search query
        $results = Book::where('title', 'like', '%' . $query . '%')
                      ->orWhere('author_name', 'like', '%' . $query . '%')
                      ->orWhereHas('category', function($query) use ($query) {
                          $query->where('name', 'like', '%' . $query . '%');
                      })
                      ->get();

        // Return the view component with the search results
        return view('components.search_results', compact('results'));
    }
}
