<x-admin-layout>
    <x-slot name="header">
        <h1 class="h3">Feedback Management</h1>
    </x-slot>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Book ID</th>
                    <th>Content</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedbacks->reverse() as $feedback)
                    <tr>
                        <td>{{ $feedback->id }}</td>
                        <td>{{ $feedback->user_id }}</td>
                        <td>{{ $feedback->book_id }}</td>
                        <td>
                            {{ Str::limit($feedback->content, 50) }}
                            @if (strlen($feedback->content) > 50)
                                <a href="#" data-toggle="modal" data-target="#feedbackModal{{ $feedback->id }}">Read More</a>

                                <!-- Modal -->
                                <div class="modal fade" id="feedbackModal{{ $feedback->id }}" tabindex="-1" aria-labelledby="feedbackModalLabel{{ $feedback->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="feedbackModalLabel{{ $feedback->id }}">Feedback Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                {{ $feedback->content }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>{{ $feedback->status }}</td>
                        <td>
                            @if ($feedback->status === 'pending')
                                <form action="{{ route('admin.feedback.approve', $feedback->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>
                                <form action="{{ route('admin.feedback.deny', $feedback->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Deny</button>
                                </form>
                            @else
                                <span class="text-muted">{{ ucfirst($feedback->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
