<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Challenge;
use App\Models\User;
use App\Models\ChallengeResult;
use App\Models\ChallengePoint;
use Illuminate\Support\Facades\Log; // Import Log facade

class ChallengeController extends Controller
{
    /**
     * Menampilkan daftar challenge dan hasilnya.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil semua user kecuali yang sedang login
        $users = User::where('id', '!=', $user->id)
            ->with('pointSummary')
            ->get(['id', 'name', 'avatar']);

        // Format data user (untuk dropdown Create Challenge)
        $users = $users->map(function ($usr) {
            return [
                'id' => $usr->id,
                'name' => $usr->name,
                'avatar' => $usr->avatar,
                'total_points' => $usr->pointSummary ? $usr->pointSummary->total_points : 0,
            ];
        });

        // Ambil challenge milik user (sebagai challenger / challenged)
        $challenges = Challenge::where('challenger_id', $user->id)
            ->orWhere('challenged_id', $user->id)
            ->with(['challenger', 'challenged', 'results'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil hasil challenge yang sudah selesai
        $results = ChallengeResult::whereHas('challenge', function ($query) use ($user) {
            $query->where('challenger_id', $user->id)
                ->orWhere('challenged_id', $user->id);
        })
            ->with(['challenge', 'winner', 'loser'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung statistik user
        $totalWins = $results->where('winner_id', $user->id)->count();
        $totalLoses = $results->where('loser_id', $user->id)->count();
        $totalMatches = $totalWins + $totalLoses;
        $winRate = $totalMatches > 0 ? round(($totalWins / $totalMatches) * 100, 2) : 0;

        // Hitung win streak dan lose streak
        $winStreak = 0;
        $loseStreak = 0;
        $currentStreak = 0;
        $lastResult = null;

        foreach ($results as $result) {
            if ($result->winner_id == $user->id) {
                if ($lastResult === 'win') {
                    $currentStreak++;
                } else {
                    $currentStreak = 1;
                }
                $lastResult = 'win';
                $winStreak = max($winStreak, $currentStreak);
            } elseif ($result->loser_id == $user->id) {
                if ($lastResult === 'lose') {
                    $currentStreak++;
                } else {
                    $currentStreak = 1;
                }
                $lastResult = 'lose';
                $loseStreak = max($loseStreak, $currentStreak);
            }
        }

        // Data user (login)
        $userPoints = $user->pointSummary ? $user->pointSummary->total_points : 0;
        $userAvatar = $user->avatar;

        return view('student.challenge.index', [
            'challenges' => $challenges,
            'results'    => $results,
            'users'      => $users,
            'userPoints' => $userPoints,
            'userAvatar' => $userAvatar,
            'totalWins'  => $totalWins,
            'totalLoses' => $totalLoses,
            'winRate'    => $winRate,
            'winStreak'  => $winStreak,
            'loseStreak' => $loseStreak,
        ]);
    }

    /**
     * Membuat challenge baru (penantang memulai tantangan).
     */
    public function createChallenge(Request $request)
    {
        $request->validate([
            'challenger_id'  => 'required|exists:users,id',
            'challenged_id'  => 'required|exists:users,id|different:challenger_id',
            'type'           => 'required|in:points,duration',
            'duration_days'  => 'required|integer|min:1|max:7',
        ]);

        $challenger = User::find($request->challenger_id);
        $challenged = User::find($request->challenged_id);

        if (!$challenger || !$challenged) {
            return redirect()->route('challenges.index')->with('error', 'Challenger or challenged user not found!');
        }

        // Log
        Log::info('Creating a new challenge:', [
            'challenger_id' => $challenger->id,
            'challenged_id' => $challenged->id,
            'type'          => $request->type,
            'duration_days' => $request->duration_days,
        ]);

        // Buat challenge baru
        $challenge = Challenge::create([
            'challenger_id'  => $request->challenger_id,
            'challenged_id'  => $request->challenged_id,
            'type'           => $request->type,
            'duration_days'  => $request->duration_days,
            'status'         => 'pending',
        ]);

        return redirect()->route('challenges.index')->with('success', 'Challenge created successfully!');
    }

    /**
     * Menghapus challenge yang masih pending.
     */
    public function deleteChallenge($challengeId)
    {
        $challenge = Challenge::findOrFail($challengeId);

        // Pastikan hanya challenger yang bisa menghapus challenge
        if ($challenge->challenger_id !== auth()->id()) {
            return redirect()->route('challenges.index')->with('error', 'You are not authorized to delete this challenge!');
        }

        // Hanya challenge dengan status pending yang bisa dihapus
        if ($challenge->status !== 'pending') {
            return redirect()->route('challenges.index')->with('error', 'Only pending challenges can be deleted!');
        }

        // Hapus challenge
        $challenge->delete();

        return redirect()->route('challenges.index')->with('success', 'Challenge deleted successfully!');
    }
}