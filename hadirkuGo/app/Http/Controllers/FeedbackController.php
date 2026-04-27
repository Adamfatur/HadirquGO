<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'latest');

        $query = Feedback::with(['user', 'likes'])->withCount('likes');

        if ($sort === 'popular') {
            $query->orderBy('likes_count', 'desc')->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $feedbacks = $query->get();
        return view('feedback.index', compact('feedbacks', 'sort'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Feedback berhasil dikirim!');
    }

    public function update(Request $request, Feedback $feedback)
    {
        if ($feedback->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah sudah lewat dari 1 jam
        if ($feedback->created_at->diffInMinutes(now()) >= 60) {
            return back()->withErrors(['Waktu untuk mengedit feedback telah habis (maksimal 1 jam setelah dikirim).']);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $feedback->update([
            'content' => $request->content,
        ]);

        return back()->with('success', 'Feedback berhasil diperbarui!');
    }

    public function destroy(Feedback $feedback)
    {
        // Hanya pemilik, Admin, atau Owner yang bisa hapus
        if ($feedback->user_id !== Auth::id() && !Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('Owner')) {
            abort(403, 'Unauthorized action.');
        }

        $feedback->delete();

        return back()->with('success', 'Feedback berhasil dihapus!');
    }

    public function toggleLike(Request $request, Feedback $feedback)
    {
        $like = FeedbackLike::where('feedback_id', $feedback->id)
            ->where('user_id', Auth::id())
            ->first();

        $isLiked = false;
        if ($like) {
            $like->delete();
        } else {
            FeedbackLike::create([
                'feedback_id' => $feedback->id,
                'user_id' => Auth::id(),
            ]);
            $isLiked = true;
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'isLiked' => $isLiked,
                'likesCount' => $feedback->likes()->count()
            ]);
        }

        return back();
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        // Only Admin or Owner can update status
        if (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('Owner')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,done',
            'admin_note' => 'nullable|string',
        ]);

        $feedback->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Status feedback diperbarui!');
    }
}
