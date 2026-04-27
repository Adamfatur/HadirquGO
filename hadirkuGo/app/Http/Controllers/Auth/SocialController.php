<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Role;
use Auth;

class SocialController extends Controller
{
    // Redirect ke Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback dari Google
    public function handleGoogleCallback()
    {
        try {
            // Retrieve the user information from Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Log Google user data for debugging purposes
            Log::info('Google User Data:', [
                'id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            // Cari pengguna berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            // Jika pengguna tidak ditemukan, buat pengguna baru
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'member_id' => 'HGO-' . strtoupper(uniqid()), // Generate member_id untuk pengguna baru
                    'password' => bcrypt('password'), // Placeholder password
                ]);

                Log::info('New user created', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'member_id' => $user->member_id,
                ]);
            } else {
                // Jika pengguna sudah ada, perbarui informasi yang diperlukan
                $user->update([
                    'name' => $googleUser->getName(),
                    'avatar' => $googleUser->getAvatar(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                ]);

                Log::info('Existing user updated', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            }

            // Check if the user has any roles; if not, assign the default "Student" role
            if ($user->roles()->count() === 0) {
                $studentRole = Role::where('name', 'Student')->first();
                if ($studentRole) {
                    $user->roles()->attach($studentRole->id);
                    Log::info('Assigned default "Student" role to user', [
                        'user_id' => $user->id,
                        'assigned_role' => 'Student',
                    ]);
                } else {
                    Log::warning('Role "Student" does not exist in the roles table');
                }
            } else {
                Log::info('User already has roles assigned', [
                    'user_id' => $user->id,
                    'user_roles' => $user->roles->pluck('name')->toArray(),
                ]);
            }

            // Attempt to log in the user
            Auth::login($user);

            // Check if the user is successfully authenticated
            if (Auth::check()) {
                Log::info('User successfully logged in', [
                    'user_id' => Auth::id(),
                ]);
            } else {
                Log::error('Failed to authenticate user after login attempt', [
                    'user_id' => $user->id,
                ]);
                return redirect('/login')->with('error', 'Gagal masuk. Silakan coba lagi.');
            }

            // Redirect the user based on their role
            if ($user->hasRole('Admin')) {
                Log::info('Redirecting user to Admin dashboard', ['user_id' => $user->id]);
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('Owner')) {
                Log::info('Redirecting user to Owner dashboard', ['user_id' => $user->id]);
                return redirect()->route('owner.dashboard');
            } elseif ($user->hasRole('Lecturer')) {
                Log::info('Redirecting user to Lecturer dashboard', ['user_id' => $user->id]);
                return redirect()->route('lecturer.dashboard');
            } elseif ($user->hasRole('Parent')) {
                Log::info('Redirecting user to Parent dashboard', ['user_id' => $user->id]);
                return redirect()->route('parent.dashboard');
            } else {
                Log::info('Redirecting user to Student dashboard', ['user_id' => $user->id]);
                return redirect()->route('student.dashboard');
            }

        } catch (\Exception $e) {
            // Log any exceptions thrown during the login process
            Log::error('Error occurred during Google login callback', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            // Redirect back to the login page with an error message
            return redirect('/login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Method logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('status', 'Anda berhasil logout');
    }
}