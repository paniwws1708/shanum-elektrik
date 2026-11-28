<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending the password reset link via email.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem.',
            ])->withInput();
        }

        // Generate token
        $token = Str::random(64);

        // Store token
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Send email with reset link
        Mail::send('emails.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ], function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Reset Password - Shanum Elektrik');
        });

        return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
    }

    /**
     * Show the reset password form (via email link).
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    /**
     * Handle the password reset (via email link).
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Find the token record
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->token, $record->token)) {
            return back()->withErrors([
                'email' => 'Token reset password tidak valid atau sudah kadaluarsa.',
            ]);
        }

        // Check if token is expired (60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->withErrors([
                'email' => 'Token reset password sudah kadaluarsa. Silakan request ulang.',
            ]);
        }

        // Update password
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        $user->update([
            'password' => $request->password, // Will be hashed automatically by the cast
        ]);

        // Delete the used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }

    // ================================================================
    // DIRECT RESET (for offline/local use without email)
    // ================================================================

    /**
     * Show the direct reset form (verify by email).
     */
    public function showDirectResetForm()
    {
        return view('auth.direct-reset-password');
    }

    /**
     * Handle the direct password reset (without email sending).
     */
    public function directResetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem.',
            ])->withInput();
        }

        $user->update([
            'password' => $request->password,
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}
