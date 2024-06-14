<x-app-layout>
    <div class="container mt-4">
        <h2>Edit Book Request</h2>
        
        @if(!isset($request->url))
        <form action="{{ route('book_requests.update', $request) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="book_title">Book Title/URL :</label>
                <input type="text" class="form-control" name="book_title" value="{{ $request->book_title ?? $request->url}}" required>
            </div>
            <div class="form-group">
                <label for="author_name">Author Name:</label>
                <input type="text" class="form-control" name="author_name" value="{{ $request->author_name }}" >
            </div>
            <div class="form-group">
                <label for="publish_date">Publish Date:</label>
                <input type="date" class="form-control" name="publish_date" value="{{ $request->publish_date }}">
            </div>
            <div class="form-group">
                <label for="reason">Reason:</label>
                <textarea class="form-control" name="reason">{{ $request->reason }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Request</button>
            <a href="{{ route('book_requests.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
        @else
        <form action="{{ route('book_requests.update', $request) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="url">URL:</label>
                <input type="url" class="form-control" name="url" value="{{ $request->url }}" required>
            </div>
            <div class="form-group">
                <label for="reason">Reason:</label>
                <textarea class="form-control" name="reason">{{ $request->reason }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Request</button>
            <a href="{{ route('book_requests.index') }}" class="btn btn-secondary"></a>
        </form>
        @endif
    </div>
</x-app-layout>
