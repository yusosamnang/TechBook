<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Socialite;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if($request->user()->userType === 'admin')
        {
            return redirect('admin/dashboard');
        }

        return redirect(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function socialLogin(Request $request, $provider): RedirectResponse
    {
        // Redirect to the social provider for authentication
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the social provider.
     */
    public function handleSocialCallback(Request $request, $provider): RedirectResponse
    {
    $socialUser = Socialite::driver($provider)->stateless()->user();

        // Ensure the social user has an email address
        if (!$socialUser->getEmail()) {
            return redirect()->route('login')->with('error', 'Email address not provided by social provider.');
        }

        // Check if the user with the provided email already exists
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // Create a new user if not found
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt('dummyPassword'), // Provide a default password
            ]);
        }

        // Log in the user
        Auth::login($user);

        return redirect()->intended('/');
    }
}
