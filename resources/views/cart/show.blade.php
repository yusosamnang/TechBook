<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
        <style>
            .button {
                display: inline-block;
                margin-top: 20px;
                padding: 10px 20px;
                background-color: blue;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                font-weight: bold;
                text-decoration: none;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .button:hover {
                background-color: #0056b3; /* Darker shade of blue on hover */
            }
        </style>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        @if($cart->count() > 0)
            <div class="flex flex-wrap justify-start -mx-4">
                @foreach($cart as $item)
                    <div class="w-full md:w-1/2 lg:w-1/3 px-4 mb-8">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                            <div class="p-4 flex-grow">
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $item['title'] }}</h3>
                                <p class="text-gray-600 mb-2">Price: ${{ $item['price'] }}</p>
                            </div>
                            <div class="bg-gray-100 px-4 py-2 flex justify-end space-x-4">
                                <form action="{{ route('cart.remove', ['id' => $item['id']]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <p class="text-lg font-semibold">Total Price: ${{ $totalPrice }}</p>
                <a href="{{ route('checkout.show') }}" class="button">Proceed to Checkout</a>
            </div>
        @else
            <div class="text-center">
                <p class="text-gray-600 text-lg">Your cart is empty.</p>
            </div>
        @endif
    </div>
</x-app-layout>
