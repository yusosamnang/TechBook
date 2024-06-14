<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Favorite Books') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        @if($favorites->isEmpty())
            <p class="text-center text-gray-600 text-lg">You haven't added any books to your favorites yet.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($favorites as $favorite)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="card">
                            <a href="{{ route('books.showDetail', $favorite->id) }}" class="flex">
                                <img src="{{ $favorite->cover_url }}" alt="Book Cover" class="w-48 h-auto object-cover rounded-lg border border-gray-300">
                                <div class="card-body ml-4">
                                    <h5 class="card-title">{{ $favorite->title }}</h5>
                                    <p class="card-text">Author: {{ $favorite->author_name }}</p>
                                    <p class="card-text">Category: {{ $favorite->category->name }}</p>
                                    <p class="card-text">Published Date: {{ \Carbon\Carbon::parse($favorite->published_date)->format('Y') }}</p>
                                </div>
                            </a>
                        </div>
                        <div class="bg-gray-100 px-4 py-2 flex justify-end">
                            <!-- Form to remove the book from favorites -->
                            <form action="{{ route('favorite.remove', ['id' => $favorite->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Remove from Favorites</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
