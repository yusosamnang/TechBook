{{-- resources/views/admin/pendingBooks.blade.php --}}

<x-admin-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">Pending Books</div>

                    <div class="card-body">
                        @if ($pendingBooks->isEmpty())
                            <p>No pending books found.</p>
                        @else
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ISBN</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingBooks as $book)
                                        <tr>
                                            <td>{{ $book->ISBN }}</td>
                                            <td>{{ $book->title }}</td>
                                            <td>{{ $book->author_name }}</td>
                                            <td>{{ $book->status }}</td>
                                            <td>
                                                <button onclick="showDetails('{{ $book->ISBN }}', '{{ $book->title }}', '{{ $book->category->name }}', '{{ $book->author_name }}', '{{ $book->published_date }}', '{{ $book->price }}', '{{ $book->cover_url }}', '{{ $book->status }}', '{{ $book->type }}', '{{ $book->book_url }}','{{ $book->updated_at }}')" class="btn btn-primary"><i class="bi bi-eye"></i> View</button>
                                                <form action="{{ route('admin.update-status', $book->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="Approved">
                                                    <button type="submit" class="btn btn-success">Approve</button>
                                                </form>
                                                <form action="{{ route('admin.update-status', $book->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="Denied">
                                                    <button type="submit" class="btn btn-danger">Deny</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Book Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12 text-center">
                            <img id="image" src="" alt="Book Cover" style="max-width: 200px;">
                        </div>
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                                <td><strong>ISBN</strong></td>
                                <td><span id="isbn"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Title</strong></td>
                                <td><span id="title"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Category</strong></td>
                                <td><span id="category"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Author</strong></td>
                                <td><span id="author"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Published Date</strong></td>
                                <td><span id="published_date"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Price</strong></td>
                                <td><span id="price"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td><span id="status"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Type</strong></td>
                                <td><span id="type"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Updated At</strong></td>
                                <td><span id="updated_at"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a id="read-and-download" href="" class="btn btn-primary btn-sm">Read and Download</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetails(isbn, title, category, author, published_date, price, cover_url, status, type, book_url, updated_at) {
            document.getElementById('isbn').innerText = isbn;
            document.getElementById('title').innerText = title;
            document.getElementById('category').innerText = category;
            document.getElementById('author').innerText = author;
            document.getElementById('published_date').innerText = published_date;
            document.getElementById('price').innerText = '$' + price;
            document.getElementById('updated_at').innerText = updated_at;

            var statusSpan = document.getElementById('status');
            statusSpan.innerText = status;
            statusSpan.classList.add('badge', 'rounded-pill', 'p-2');
            if (status.toLowerCase() === 'approved') {
                statusSpan.classList.add('bg-success');
            } else if (status.toLowerCase() === 'denied') {
                statusSpan.classList.add('bg-danger');
            } else if (status.toLowerCase() === 'pending') {
                statusSpan.classList.add('bg-warning');
            }

            var typeSpan = document.getElementById('type');
            typeSpan.innerText = type;
            typeSpan.classList.add('badge', 'rounded-pill', 'p-2');
            if (type.toLowerCase() === 'free') {
                typeSpan.classList.add('bg-primary');
            } else if (type.toLowerCase() === 'paid') {
                typeSpan.classList.add('bg-danger');
            }

            document.getElementById('image').src = cover_url;
            document.getElementById('read-and-download').href = "/adminPdfViewer?url=" + encodeURIComponent(book_url);

            var modal = new bootstrap.Modal(document.getElementById('detailsModal'), {
                keyboard: false
            });
            modal.show();
        }
    </script>
</x-admin-layout>
