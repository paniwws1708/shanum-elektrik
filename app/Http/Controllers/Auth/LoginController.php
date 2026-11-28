<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            $user = Auth::user();
            ActivityLog::catat('Auth', 'login', "{$user->name} login ke sistem", $user->id);

            return match ($user->role) {
                'admin' => redirect()->intended(route('admin.dashboard')),
                'staff_barang' => redirect()->intended(route('staff.dashboard')),
                'kasir' => redirect()->intended(route('kasir.dashboard')),
                default => redirect()->intended(route('dashboard')),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::catat('Auth', 'logout', "{$user->name} logout dari sistem", $user->id);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }
}
