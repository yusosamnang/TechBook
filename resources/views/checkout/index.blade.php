<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('checkout.process') }}" method="POST" id="payment-form">
                    @csrf
                    <div class="form-group">
                        <label for="card-element">
                            Credit or debit card
                        </label>
                        <div id="card-element" class="card-input">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>
                        <!-- Used to display form errors. -->
                        <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                    </div>
                    <div class="form-group">
                        <label for="totalAmount">
                            Total Amount
                        </label>
                        <input type="text" id="totalAmount" class="form-control" value="{{ $totalAmount }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="items">
                            Items
                        </label>
                        <ul id="items" class="list-group">
                            @foreach($cartItems as $item)
                                <li class="list-group-item">{{ $item['title'] }} - ${{ $item['price'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="submit" class="btn btn-custom btn-block">Submit Payment</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');

        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    var tokenInput = document.createElement('input');
                    tokenInput.setAttribute('type', 'hidden');
                    tokenInput.setAttribute('name', 'stripeToken');
                    tokenInput.setAttribute('value', result.token.id);
                    form.appendChild(tokenInput);

                    // Optionally, you can disable the submit button to prevent multiple submissions
                    form.querySelector('button[type="submit"]').disabled = true;

                    // Submit the form programmatically
                    form.submit();
                }
            });
        });
    </script>
</x-app-layout>
