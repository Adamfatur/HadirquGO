<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Menampilkan daftar user bisnis (staff, member tim, dan manager tim) dalam format JSON,
     * dengan indikasi apakah user adalah member tim atau manager tim dan pagination.
     * Dilengkapi dengan fitur search filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $business_unique_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $business_unique_id)
    {
        // Cari bisnis berdasarkan business_unique_id
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        // Ambil semua staff (untuk mendapatkan daftar user terkait bisnis)
        $staffMembers = $business->staff()->with('user')->get();

        // Ambil semua tim di bisnis ini dengan relasi members dan managers
        $teams = $business->teams()->with(['members', 'managers'])->get(); // Eager load 'managers' juga

        // Kumpulkan semua member tim dan manager tim dari semua tim dalam bisnis
        $teamMembers = collect();
        $teamManagers = collect(); // Koleksi untuk menyimpan manager tim
        foreach ($teams as $team) {
            $teamMembers = $teamMembers->merge($team->members);
            $teamManagers = $teamManagers->merge($team->managers); // Kumpulkan manager tim
        }

        // Gabungkan semua user: staff, member tim, dan manager tim, lalu hilangkan duplikasi
        $allUsers = $staffMembers->pluck('user')->merge($teamMembers)->merge($teamManagers)->unique('id')->values();

        // Fitur Search Filter
        $searchTerm = $request->input('search');
        $filteredUsers = $allUsers; // Inisialisasi dengan semua user

        if ($searchTerm) {
            $filteredUsers = $allUsers->filter(function ($user) use ($searchTerm) {
                return stripos($user->name, $searchTerm) !== false || stripos($user->email, $searchTerm) !== false;
            });
        }

        $totalUsersCount = $filteredUsers->count(); // Hitung total user setelah filter

        // Format data user untuk JSON dan Pagination
        $perPage = 10;
        $page = request()->get('page', 1);
        $startIndex = ($page - 1) * $perPage;
        $paginatedUsers = $filteredUsers->slice($startIndex, $perPage)->map(function ($user) use ($business, $teams) {
            $isTeamMember = false;
            $isTeamManager = false;

            foreach($teams as $team){
                if($team->members->contains($user)){
                    $isTeamMember = true;
                }
                if($team->managers->contains($user)){
                    $isTeamManager = true;
                }
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ?? 'default.jpg',
                'is_team_member' => $isTeamMember,
                'is_team_manager' => $isTeamManager,
            ];
        });


        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedUsers,
            $filteredUsers->count(), // Gunakan count dari $filteredUsers setelah filter
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        $users->appends(['search' => $searchTerm]); // Pertahankan search term di pagination link


        // Kembalikan View Blade dengan data dan pagination
        return view('owner.members.index', [
            'business' => $business,
            'users' => $users, // Kirim paginated user yang sudah difilter
            'totalUsersCount' => $totalUsersCount, // Kirim total user count ke view
            'searchTerm' => $searchTerm, // Kirim search term ke view agar input field tetap terisi
        ]);
    }
}