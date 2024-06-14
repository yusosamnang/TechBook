<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tech Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
</head>
<body>
<x-admin-layout>
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
        <form method="POST" action="/admin/categories/update/{{$categories->id}}">
            @csrf
            <div class="form-group mb-2">
                <label for="exampleInputName">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{$categories->name}}">
            </div>
            <button type="submit" class="btn btn-primary" onclick="return validateForm()">Update</button>
        </form>
        <a href="/admin/categories" class="btn btn-secondary mt-3">Back to Category</a>
    </div>

    

    <script>
        function validateForm() {
            var categoryName = document.getElementById("name").value;
            if (categoryName.trim() === "") {
                alert("Please enter a category name");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>
    </x-admin-layout>
</body>
</html>
