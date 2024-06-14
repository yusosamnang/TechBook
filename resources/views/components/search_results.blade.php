<!-- view/components/search_results.blade.php -->

@if ($results->count() > 0)
    <ul>
        @foreach ($results as $result)
            <li>{{ $result->title }}</li>
            <!-- Display other book information as needed -->
        @endforeach
    </ul>
@else
    <p>No results found</p>
@endif
