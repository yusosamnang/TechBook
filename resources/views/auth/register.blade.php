<x-guest-layout>
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mx-auto d-block"/>
    </div>

    <x-auth-session-status class="mb-2" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}" class="container">
        @csrf

        <div class="text-center h4">
            <b>Create an account</b>
        </div>

        <!-- Name -->
        <div class="form-group mt-2">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
        </div>

        <!-- Email Address -->
        <div class="form-group mt-2">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
        </div>

        <!-- Password -->
        <div class="form-group mt-2">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group mt-2">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
        </div>

        <div class="text-center mt-2">
            <p>Or sign up with:</p>
            <a href="{{ url('login/google') }}" class="btn btn-danger px-4">Google</a>
            <a href="{{ url('login/facebook') }}" class="btn btn-info px-3">Facebook</a>
        </div>

        <div class="d-flex justify-content-center align-items-center mt-4">
            <button class="btn btn-primary w-100" type="submit">
                {{ __('Register') }}
            </button>
        </div>

        <div class="text-center mt-1">
            <p>Already have an account? <a href="{{ route('login') }}">Log in</a></p>
        </div>
    </form>
</x-guest-layout>
