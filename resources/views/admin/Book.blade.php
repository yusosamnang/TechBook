<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
<x-admin-layout>
<div class="container mt-5">
    <h2>Books</h2>
    <a href="/admin/books/add" class="btn btn-success mb-3">Add New Book</a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ISBN</th>
                <th scope="col">Title</th>
                <th scope="col">Category</th>
                <th scope="col">Author</th>
                <th scope="col">Published Date</th>
                <th scope="col">Price</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (isset($books) && count($books) > 0)
                @foreach ($books->reverse() as $book)
                    <tr>
                        <td>{{ $book->ISBN }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->category->name }}</td> <!-- Display category name -->
                        <td>{{ $book->author_name }}</td>
                        <td>{{ $book->published_date }}</td>
                        <td>${{ number_format($book->price, 2) }}</td>
                        <td>
                            <!-- View button -->
                            <button onclick="showDetails('{{ $book->ISBN }}', '{{ $book->title }}', '{{ $book->category->name }}', '{{ $book->author_name }}', '{{ $book->published_date }}', '{{ $book->price }}', '{{ $book->cover_url }}', '{{ $book->status }}', '{{ $book->type }}', '{{ $book->book_url }}','{{ $book->updated_at }}')" class="btn btn-primary"><i class="bi bi-eye"></i> View</button>
                            
                            <!-- Edit button -->
                            <a href="/admin/books/edit/{{ $book->id }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                            
                            <!-- Delete button -->
                            <button onclick="confirmDelete('{{ $book->id }}')" class="btn btn-danger" action="/admin/books/delete/{{ $book->id }}"><i class="bi bi-trash"></i> Delete</button>

                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7">No Books Found</td>
                </tr>
            @endif
        </tbody>
    </table>
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

    // Set status and type with custom classes for highlighting
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
     // Set the href attribute of the "Read" button to the book_url for viewing
     document.getElementById('read-and-download').setAttribute('href', book_url);

    var modal = new bootstrap.Modal(document.getElementById('detailsModal'), {
        keyboard: false
    });
    modal.show();
}


function confirmDelete(bookId) {
    if (confirm("Are you sure you want to delete this book?")) {
        // Send an AJAX request with DELETE method
        fetch(`/admin/books/delete/${bookId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Ensure CSRF token is sent
            }
        }).then(response => {
            if (response.ok) {
                // Reload the page after successful delete
                window.location.reload();
            } else {
                // Handle specific error responses here
                if (response.status === 405) {
                    alert('Deleted Success.');
                } 
                else {
                    alert('Failed to delete book. Please try again later.');
                }
            }
        }).catch(error => {
            console.error('Error deleting book:', error);
            alert('Failed to delete book. Please try again.');
        });
    
    }
}


</script>


<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</x-admin-layout>
</body>
</html>
