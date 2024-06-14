<x-guest-layout>
  <div class="text-center mb-4">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid mx-auto d-block"/>
  </div>

  <x-auth-session-status class="mb-2" :status="session('status')" />

  <form method="POST" action="{{ route('login') }}" class="container mt-2">
    @csrf

    <div class="text-center h4">
      <b>Login into your account!</b>
    </div>

    <div class="form-group">
      <x-input-label for="email" :value="__('Email')" />
      <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
      <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
    </div>

    <div class="form-group mt-4">
      <x-input-label for="password" :value="__('Password')" />
      <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
      <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
      <div class="form-check">
        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
        <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
      </div>
      @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
          {{ __('Forgot your password?') }}
        </a>
      @endif
    </div>

    <div class="text-center mt-4">
      <p>Or login with:</p>
      <a href="{{ url('login/google') }}" class="btn btn-danger px-4">Google</a>
      <a href="{{ url('login/facebook') }}" class="btn btn-info px-3">Facebook</a>
    </div>

    <div class="d-flex justify-content-center align-items-center mt-4">
      <button class="btn btn-primary ms-3 w-100" type="submit">
        {{ __('Log in') }}
      </button>
    </div>

    <div class="text-center mt-4">
      <p>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
    </div>
  </form>
</x-guest-layout>
