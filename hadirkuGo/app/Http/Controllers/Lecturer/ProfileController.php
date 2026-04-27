<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Biodata;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil.
     */
    public function show()
    {
        $user = auth()->user()->load(['biodata', 'leaderboards' => function($q) { $q->where('category', 'top_points'); }, 'pointSummary', 'userAchievements']);
        
        $levels = \App\Models\Level::orderBy('minimum_points', 'asc')->get();
        $userPts = $user->pointSummary->total_points ?? 0;
        $currentLevel = $levels->last(fn($l) => $userPts >= $l->minimum_points);
        $levelNumber = $currentLevel ? $levels->search(fn($l) => $l->id === $currentLevel->id) + 1 : 0;
        $lbEntry = $user->leaderboards->first();
        
        $profileData = [
            'level_name' => $currentLevel?->name ?? 'Pioneer',
            'level_number' => $levelNumber,
            'level_image' => $currentLevel?->image_url,
            'total_points' => $userPts,
            'rank' => $lbEntry?->current_rank,
            'title' => $lbEntry?->title,
            'frame_color' => $lbEntry?->frame_color,
            'total_achievements' => $user->userAchievements->count(),
            'total_sessions' => $user->attendances()->count(),
        ];

        return view('lecturer.profile.show', compact('user', 'profileData'));
    }

    /**
     * Menampilkan halaman edit profil umum (tidak termasuk birth_date).
     */
    public function edit()
    {
        // Memuat user beserta relasi biodata
        $user = auth()->user()->load('biodata');
        return view('lecturer.profile.edit', compact('user'));
    }

    /**
     * Memperbarui informasi profil umum.
     * TIDAK Termasuk pembaruan birth_date.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi input, excluding birth_date
        $request->validate([
            'phone_number' => 'nullable|string|max:15',
            'id_number' => 'nullable|string|max:50',
            'other_id_number' => 'nullable|string|max:50',
            'nickname' => 'nullable|string|max:50',
            'about' => 'nullable|string',
            // 'birth_date' dihapus karena diatur terpisah
        ]);

        // Log input data untuk debugging
        Log::info('Profile update request received', $request->all());

        // Ambil data yang akan diperbarui
        $data = $request->only([
            'phone_number',
            'id_number',
            'other_id_number',
            'nickname',
            'about',
            // 'birth_date' diabaikan di sini
        ]);

        // Update atau buat biodata dengan data yang diperbarui
        $biodata = $user->biodata()->updateOrCreate(
            ['user_id' => $user->id], // Kondisi jika biodata sudah ada
            $data // Data yang akan diperbarui
        );

        // Log data yang disimpan untuk debugging
        Log::info('Biodata updated or created: ', $biodata->toArray());

        // Redirect kembali ke halaman profil dengan pesan sukses
        return redirect()->route('lecturer.profile.show')->with('success', 'Profile updated successfully.');
    }

    /**
     * Memperbarui tanggal lahir.
     * Hanya dapat diatur sekali dan tidak dapat diubah setelah diatur.
     */
    public function updateBirthDate(Request $request)
    {
        $user = auth()->user();

        // Validasi input untuk birth_date
        $request->validate([
            'birth_date' => 'required|date|before:today',
        ]);

        // Periksa apakah tanggal lahir sudah diatur
        if ($user->biodata && $user->biodata->birth_date) {
            // Jika sudah diatur, kembalikan dengan error
            return redirect()->route('lecturer.profile.show')->withErrors([
                'birth_date' => 'Birth date can only be set once and cannot be changed.'
            ]);
        }

        // Update atau buat biodata dengan tanggal lahir
        $biodata = $user->biodata()->updateOrCreate(
            ['user_id' => $user->id],
            ['birth_date' => $request->birth_date]
        );

        // Log data yang disimpan untuk debugging
        Log::info('Birth date updated: ', $biodata->toArray());

        // Redirect kembali ke halaman profil dengan pesan sukses
        return redirect()->route('lecturer.profile.show')->with('success', 'Birth date updated successfully.');
    }

    /**
     * Update user name (one-time only).
     */
    public function updateName(Request $request)
    {
        $user = auth()->user();

        if ($user->name_changed) {
            return redirect()->route('lecturer.profile.show')->withErrors(['name' => 'Name can only be changed once.']);
        }

        $request->validate([
            'name' => 'required|string|min:3|max:100|regex:/^[a-zA-Z\s\.]+$/',
        ], [
            'name.regex' => 'Name can only contain letters, spaces, and dots.',
        ]);

        $user->update(['name' => $request->name, 'name_changed' => true]);

        return redirect()->route('lecturer.profile.show')->with('success', 'Name updated successfully.');
    }
}
