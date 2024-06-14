<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome') }}
        </h2>
    </x-slot>

    <style>
        .categories {
            text-align: right;
            margin: 20px;
        }
        .content {
            text-align: center;
            margin: 20px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            justify-items: center;
        }
        .card {
            display: flex;
            align-items: stretch;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-right: 25px;
            margin-top: 25px;
        }

        .card img {
            width: 120px;
            height: 100%;
            object-fit: cover;
            flex-shrink: 0;
        }
        
        .search-bar {
            position: relative;
            width: 550px;
            height: 20px;
            flex-grow: 1;
            margin: 32px 10px 50px;
        }
        .search-input {
            padding: 5px 10px;
            width: 100%;
            border: 2px solid white;
            border-radius: 20px;
            outline: none;
            color: #007BFF;
            background-color: rgb(111, 255, 214);
        }

        .card-body {
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-grow: 1;
        }

        .card-title {
            font-size: 18px;
            margin: 0 0 10px 0;
            
        }

        .card-text {
            font-size: 14px;
        }

        .pagination {
            text-align: center;
            margin: 20px;
        }
    </style>

    <div class="container">
        <div class="categories">
            <div class="dropdown">
                <button class="btn btn-danger dropdown-toggle" type="button" id="categoriesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Categories
                </button>
                <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                    <li><a class="dropdown-item" href="{{ route('home') }}">All Categories</a></li>
                    @foreach ($categories as $category)
                        <li><a class="dropdown-item" href="{{ route('category.books', $category->id) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="content">
            <p class="h2 text-left">Technology always update, upgrade your skill now</p>
            <p class="h4 text-left">The Newest technology</p>

            <!-- Display Books -->
            <div class="grid">
                @forelse ($books as $book)
                    @if ($book->status === 'Approved')
                        <div class="card">
                            <a href="{{ route('books.showDetail', $book->id) }}" class="d-flex">
                                <img src="{{ $book->cover_url }}" alt="Book Cover">
                                <div class="card-body text-left">
                                    <h5 class="card-title">{{ $book->title }}</h5>
                                    <p class="card-text">Author: {{ $book->author_name }}</p>
                                    <p class="card-text">Category: {{ $book->category->name }}</p>
                                    <p class="card-text">Published Date: {{ \Carbon\Carbon::parse($book->published_date)->format('Y') }}</p>
                                </div>
                            </a>
                        </div>
                    @endif
                @empty
                    <p>No books found.</p>
                @endforelse
            </div>

            <!-- Pagination Links -->
            <div class="pagination">
                {{ $books->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    
</x-app-layout>
