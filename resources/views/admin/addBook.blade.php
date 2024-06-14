<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
    @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('error') }}
        </div>
    @endif

    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h2>Add New Book</h2>
    <form id="addBookForm" method="POST" action="/admin/books/addnew" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="ISBN" class="form-label">ISBN</label>
            <input type="text" class="form-control" name="ISBN" id="ISBN" placeholder="ISBN">
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Title">
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" name="category" id="category">
                <option value="" selected disabled>Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="author_name" class="form-label">Author Name</label>
            <input type="text" class="form-control" name="author_name" id="author_name" placeholder="Author Name">
        </div>
        <div class="mb-3">
            <label for="published_date" class="form-label">Published Date</label>
            <input type="date" class="form-control" name="published_date" id="published_date">
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" name="price" id="price" placeholder="$">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" id="status">
                <option value="Approved" selected>Approved</option>
                <option value="Denied">Denied</option>
                <option value="Pending">Pending</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-select" name="type" id="type">
                <option value="Free" selected>Free</option>
                <option value="Paid">Paid</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="cover_image" class="form-label">Cover Image</label>
            <input type="file" class="form-control" name="cover_image" id="cover_image">
        </div>
        <div class="mb-3">
            <label for="book_file" class="form-label">Book File (PDF)</label>
            <input type="file" class="form-control" name="book_file" id="book_file">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    
    <a href="/admin/books" class="btn btn-secondary mt-3">Back to Books</a>
</div>

<script>
    // You can include JavaScript validation here if needed
</script>

</body>
</html>