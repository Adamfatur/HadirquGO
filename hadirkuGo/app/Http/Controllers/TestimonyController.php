<?php

namespace App\Http\Controllers;

use App\Models\Testimony;
use App\Models\User;
use Illuminate\Http\Request;

class TestimonyController extends Controller
{
    /**
     * Menampilkan daftar testimoni
     */
    public function index()
    {
        $testimonies = Testimony::with('user')->get();
        return response()->json($testimonies);
    }

    /**
     * Menyimpan testimoni baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'testimony' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $testimony = Testimony::create($request->all());
        return response()->json($testimony, 201);
    }

    /**
     * Menampilkan detail testimoni
     */
    public function show($id)
    {
        $testimony = Testimony::with('user')->findOrFail($id);
        return response()->json($testimony);
    }

    /**
     * Mengupdate testimoni
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'testimony' => 'sometimes|required|string',
            'rating' => 'sometimes|required|integer|between:1,5',
        ]);

        $testimony = Testimony::findOrFail($id);
        $testimony->update($request->all());
        return response()->json($testimony);
    }

    /**
     * Menghapus testimoni
     */
    public function destroy($id)
    {
        $testimony = Testimony::findOrFail($id);
        $testimony->delete();
        return response()->json(null, 204);
    }

    /**
     * Menampilkan testimoni berdasarkan user tertentu
     */
    public function getUserTestimonies($userId)
    {
        $user = User::findOrFail($userId);
        $testimonies = $user->testimonies;
        return response()->json($testimonies);
    }
}
