<x-app-layout>
    <!-- Centered Request New Book Button -->
    <div class="d-flex justify-content-center mt-4">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#requestBookModal">
            Request New Book
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="requestBookModal" tabindex="-1" aria-labelledby="requestBookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestBookModalLabel">Request New Book</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" id="requestTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">By Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="url-tab" data-toggle="tab" href="#url" role="tab" aria-controls="url" aria-selected="false">By URL</a>
                        </li>
                    </ul>
                    <!-- Tab Content -->
                    <div class="tab-content" id="requestTabContent">
                        <!-- Request Book by Info Form -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                            <form action="{{ route('book_requests.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="book_title">Book Title:</label>
                                    <input type="text" class="form-control" name="book_title" required>
                                </div>
                                <div class="form-group">
                                    <label for="author_name">Author Name:</label>
                                    <input type="text" class="form-control" name="author_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="publish_date">Publish Date:</label>
                                    <input type="date" class="form-control" name="publish_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="reason">Reason:</label>
                                    <textarea class="form-control" name="reason" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                            </form>
                        </div>
                        <!-- Request Book by URL Form -->
                        <div class="tab-pane fade" id="url" role="tabpanel" aria-labelledby="url-tab">
                            <form action="{{ route('book_requests.store') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="url">URL:</label>
                                    <input type="url" class="form-control" name="url" required>
                                </div>
                                <div class="form-group">
                                    <label for="reason">Reason:</label>
                                    <textarea class="form-control" name="reason" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Your Requests Table -->
    <div class="container mt-4">
    <h2>Your Requests</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Title/URL</th>
                <th style="width: 20%;">Author</th>
                <th>Publish Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
            <tr>
                <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                    {{ $request->book_title ?? $request->url }}
                </td>
                <td style="width: 20%;">{{ $request->author_name }}</td>
                <td>{{ $request->publish_date }}</td>
                <td>{{ $request->reason }}</td>
                <td>
                    @if ($request->status === 'approved')
                        <span class="badge badge-success">Approved</span>
                    @elseif ($request->status === 'denied')
                        <span class="badge badge-warning">Denied</span>
                    @else
                        <span class="badge badge-info">Pending</span>
                    @endif
                </td>
                <td>
                    @if ($request->status === 'pending')
                        <a href="{{ route('book_requests.edit', $request->id) }}" class="btn btn-info">Edit</a>
                        <form action="{{ route('book_requests.destroy', $request->id) }}" method="post" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>
