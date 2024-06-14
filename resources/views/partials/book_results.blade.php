@foreach ($books as $book)
    <div class="card">
        <a href="{{ route('books.showDetail', $book->id) }}">
            <img src="{{ $book->cover_url }}" alt="Book Cover" class="card-img">
        </a>
        <div class="card-content">
            <div class="card-header">
                <a href="{{ route('books.showDetail', $book->id) }}">
                    <h3>{{ $book->title }}</h3>
                </a>
            </div>
            <div class="card-body">
                <p>Author: {{ $book->author_name }}</p>
                <p>Category: {{ $book->category->name }}</p>
                <p>Published Date: {{ \Carbon\Carbon::parse($book->published_date)->format('Y') }}</p>
            </div>
        </div>
    </div>
@endforeach
