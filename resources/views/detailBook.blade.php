<!-- resources/views/books/show.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechBook</title>
    <link rel="stylesheet" href="{{ asset('css/detailBook.css') }}">
</head>
<body>
    <style>
        li {
            margin-top: 10px;
        }
        .container {
            position: relative;
        }
        .action-buttons {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
        }
        .action-buttons button {
            margin-left: 3px;
            padding: 10px;
            width: 100px;
            height: 70px;
        }
        .book-cover {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
        .feedback-section {
            margin-top: 20px;
        }
        .feedback-section h3 {
            margin-bottom: 15px;
        }
        .feedback-section .feedback {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .feedback-section .feedback p {
            margin: 0;
        }
    </style>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Welcome') }}
            </h2>
        </x-slot>
        <div class="container p-6">
            <div class="row book-details">
                <div class="col-md-4">
                    <img src="{{ $book->cover_url }}" alt="Book Cover" class="book-cover">
                </div>
                <div class="col-md-8 p-4 mb-4">
                    <p class="h3">{{ $book->title }} - <small>{{ $book->author_name }}</small></p>
                    <ul>
                        <li><strong>Category:</strong> {{ $book->category->name }}</li>
                        <li><strong>Publish Date:</strong> {{ \Carbon\Carbon::parse($book->published_date)->format('d M Y') }}</li>
                        <li><strong>ISBN:</strong> {{ $book->ISBN }}</li>
                        <li><strong>Type:</strong> {{ $book->type }}</li>
                        <li><strong>Price:</strong> ${{ $book->price }}</li>
                    </ul>
                    <div class="action-buttons">
                        @if($book->type === 'Paid')
                            <form action="{{ route('cart.add', ['id' => $book->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <button class="btn btn-primary" onclick="showDetails('{{ $book->book_url }}')">
                                Read Book
                            </button>
                        @endif
                        <form action="{{ route('favorite.add', ['id' => $book->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                Save to Favorite
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Feedback Section -->
            <div class="row feedback-section">
                <div class="col-md-12">
                    <h3>Leave Feedback</h3>
                    <form action="{{ route('feedback.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <textarea placeholder="Write your feedback about this book..." name="content" class="form-control"></textarea>
                        @error('content')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-primary mt-2">Submit Feedback</button>
                    </form>
                </div>
            </div>

            <!-- Display Feedback -->
            <div class="row feedback-section">
                <div class="col-md-12">
                    <h3>Feedback</h3>
                    @foreach($book->feedbacks->whereIn('status', ['pending', 'approved']) as $feedback)
                        <div class="feedback">
                            <p><strong>{{ $feedback->user->name }}</strong>
                                @if($feedback->created_at)
                                    ({{ $feedback->created_at->format('d M Y') }}):
                                @else
                                    (Date not available):
                                @endif
                            </p>
                            <p>{{ $feedback->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-app-layout>
    
    <script>
        function showDetails(book_url){
            window.location.href = "{{ route('pdf.viewer') }}?url=" + encodeURIComponent(book_url);
        }
    </script>
</body>
</html>
