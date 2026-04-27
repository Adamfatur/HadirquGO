<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user beserta tim yang mereka pimpin dan ikuti.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Query dasar dengan relasi teamsLed dan teamsJoined, diurutkan berdasarkan nama
        $query = User::with(['teamsLed', 'teamsJoined', 'leaderboards' => function($q) { $q->where('category', 'top_points'); }, 'pointSummary'])
            ->orderBy('name', 'asc');

        // Filter berdasarkan nama (MySQL LIKE is case-insensitive by default with utf8 collation)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Filter berdasarkan tim yang dipimpin
        if ($request->filled('team_led')) {
            $query->whereHas('teamsLed', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('team_led') . '%');
            });
        }

        // Filter berdasarkan tim yang diikuti
        if ($request->filled('team_joined')) {
            $query->whereHas('teamsJoined', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('team_joined') . '%');
            });
        }

        // Ambil data user berdasarkan query dengan pagination
        $users = $query->paginate(25)->withQueryString();

        // Hitung statistik dalam 1 query
        $stats = User::selectRaw("
            COUNT(*) as total_users,
            SUM(CASE WHEN EXISTS (SELECT 1 FROM teams WHERE teams.leader_id = users.id) THEN 1 ELSE 0 END) as total_leaders,
            SUM(CASE WHEN EXISTS (SELECT 1 FROM team_members WHERE team_members.user_id = users.id) THEN 1 ELSE 0 END) as total_members
        ")->first();

        $totalUsers = $stats->total_users;
        $totalLeaders = $stats->total_leaders;
        $totalMembers = $stats->total_members;

        // Tampilkan view dengan data users dan statistik
        return view('lecturer.users.index', compact('users', 'totalUsers', 'totalLeaders', 'totalMembers'));
    }
}