<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Redirect if already authenticated
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Find user by username
        $user = User::where('username', $credentials['username'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'login_error' => 'Username atau password salah.',
                ]);
        }

        // Login the user
        Auth::login($user, $request->filled('remember'));

        // Regenerate session to prevent session fixation
        $request->session()->regenerate();

        // Redirect based on role
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form')->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'Owner':
                return redirect()->route('owner.dashboard');

            case 'Production Staff':
                return redirect()->route('production.dashboard');

            case 'Distribution Staff':
                return redirect()->route('distribution.dashboard');

            default:
                // Fallback: logout user if role is invalid
                Auth::logout();
                return redirect()->route('login.form')->withErrors([
                    'login_error' => 'Role tidak valid.'
                ]);
        }
    }
}
