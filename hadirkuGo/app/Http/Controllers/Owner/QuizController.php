<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // [ GET ] /owner/businesses/{business}/quizzes
    public function index($businessUniqueId)
    {
        // Cari bisnis berdasarkan business_unique_id
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();

        // Cek kepemilikan bisnis
        if ($business->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Dapatkan semua quiz pada bisnis ini
        $quizzes = $business->quizzes()->latest()->get();

        return view('owner.quizzes.index', compact('business', 'quizzes'));
    }

    // [ GET ] /owner/businesses/{business}/quizzes/create
    // [ GET ] /owner/businesses/{business_unique_id}/quizzes/create
    public function create($businessUniqueId)
    {
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();

        if ($business->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('owner.quizzes.create', compact('business'));
    }

// [ POST ] /owner/businesses/{business_unique_id}/quizzes
    public function store(Request $request, $businessUniqueId)
    {
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();

        if ($business->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $business->quizzes()->create([
            'title' => $request->title,
        ]);

        return redirect()
            ->route('owner.quizzes.index', $business->business_unique_id)
            ->with('success', 'Quiz created successfully.');
    }

    // [ GET ] /owner/businesses/{business}/quizzes/{quiz}
    public function show($businessUniqueId, Quiz $quiz)
    {
        // Cari bisnis berdasarkan business_unique_id
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();

        // Pastikan user adalah owner dan quiz ini milik business yang ditemukan
        if ($business->owner_id !== Auth::id() || $quiz->business_id !== $business->id) {
            abort(403, 'Unauthorized action.');
        }

        // Dapatkan soal-soal yang ada di kuis ini (tambahkan ->with('options') jika diperlukan)
        $questions = $quiz->questions()->get();

        return view('owner.quizzes.show', compact('business', 'quiz', 'questions'));
    }

    // [ GET ] /owner/businesses/{business}/quizzes/{quiz}/edit
    public function edit($businessUniqueId, Quiz $quiz)
    {
        // Cari bisnis berdasarkan business_unique_id
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();

        // Cek kepemilikan dan keterkaitan quiz dengan bisnis
        if ($business->owner_id !== Auth::id() || $quiz->business_id !== $business->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('owner.quizzes.edit', compact('business', 'quiz'));
    }

// [ PUT ] /owner/businesses/{businessUniqueId}/quizzes/{quiz}
    public function update(Request $request, $businessUniqueId, Quiz $quiz)
    {
        // Cari bisnis berdasarkan business_unique_id
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();

        // Cek kepemilikan dan keterkaitan quiz dengan bisnis
        if ($business->owner_id !== Auth::id() || $quiz->business_id !== $business->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $quiz->update([
            'title' => $request->title,
        ]);

        return redirect()
            ->route('owner.quizzes.index', $business->business_unique_id)
            ->with('success', 'Quiz updated successfully.');
    }

// [ DELETE ] /owner/businesses/{businessUniqueId}/quizzes/{quiz}
    public function destroy($businessUniqueId, Quiz $quiz)
    {
        // Cari bisnis berdasarkan business_unique_id
        $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();

        // Cek kepemilikan dan keterkaitan quiz dengan bisnis
        if ($business->owner_id !== Auth::id() || $quiz->business_id !== $business->id) {
            abort(403, 'Unauthorized action.');
        }

        $quiz->delete();

        return redirect()
            ->route('owner.quizzes.index', $business->business_unique_id)
            ->with('success', 'Quiz deleted successfully.');
    }
}