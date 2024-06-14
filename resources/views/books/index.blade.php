<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Books') }}
        </h2>
    </x-slot>
    <div class="container">
        <h2>Your Books</h2>
        <div class="mb-3">
            <a href="{{ route('user.books.create') }}" class="btn btn-primary">Add New Book</a>
        </div>
        @if ($books->isEmpty())
            <p>No books found.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author Name</th>
                        <th>Published Date</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books->reverse() as $book)
                        <tr>
                            <td>{{ $book->ISBN }}</td>
                            <td>{{ $book->title }}</td>
                            <td>{{ $book->author_name }}</td>
                            <td>{{ $book->published_date }}</td>
                            <td>{{ $book->price }}</td>
                            <td>{{ $book->status }}</td>
                            <td>{{ $book->type }}</td>
                            <td>
                                @if ($book->user_id == Auth::id() && $book->status === 'Pending')
                                    <a href="{{ route('user.books.edit', $book->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('user.books.delete', $book->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
