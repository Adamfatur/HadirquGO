<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
//use user
use App\Models\User;
//use attendancelocation model
use App\Models\AttendanceLocation;
use App\Mail\RedemptionRequestNotification;
use Illuminate\Support\Facades\Mail;
use App\Models\Team;
use App\Models\Attendance;
use Illuminate\Support\Facades\Log;
//request

class BusinessManagementController extends Controller
{
    /**
     * Tampilkan daftar bisnis milik owner yang sedang login.
     */
    public function index()
    {
        $businesses = Auth::user()->businesses;
        return view('owner.businesses.index', compact('businesses'));
    }

    /**
     * Tampilkan form untuk menambahkan bisnis baru.
     */
    public function create()
    {
        return view('owner.businesses.create');
    }

    /**
     * Simpan bisnis baru ke dalam database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        // Buat bisnis dengan unique ID dan assign ke owner yang sedang login
        $business = new Business();
        $business->name = $request->name;
        $business->business_unique_id = 'HGO-BNS' . strtoupper(Str::random(8));
        $business->owner_id = Auth::id();
        $business->contact_person = Auth::user()->name;
        $business->contact_email = Auth::user()->email;
        $business->contact_phone = $request->contact_phone;
        $business->save();

        return redirect()->route('owner.businesses.index')->with('success', 'Business created successfully');
    }

    /**
     * Tampilkan detail bisnis.
     */
    public function show($id)
    {
        $business = Business::where('id', $id)->where('owner_id', Auth::id())->firstOrFail();
        return view('owner.businesses.show', compact('business'));
    }

    /**
     * Tampilkan form untuk mengedit bisnis.
     */
    public function edit($id)
    {
        $business = Business::where('id', $id)->where('owner_id', Auth::id())->firstOrFail();
        return view('owner.businesses.edit', compact('business'));
    }

    /**
     * Perbarui data bisnis yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        $business = Business::where('id', $id)->where('owner_id', Auth::id())->firstOrFail();
        $business->name = $request->name;
        $business->contact_phone = $request->contact_phone;
        $business->save();

        return redirect()->route('owner.businesses.index')->with('success', 'Business updated successfully');
    }

    /**
     * Hapus bisnis dari database.
     */
    public function destroy($id)
    {
        $business = Business::where('id', $id)->where('owner_id', Auth::id())->firstOrFail();
        $business->delete();

        return redirect()->route('owner.businesses.index')->with('success', 'Business deleted successfully');
    }

    public function manage($business_unique_id)
    {
        // Ambil data bisnis beserta relasi yang diperlukan
        $business = Business::where('business_unique_id', $business_unique_id)
            ->with(['lecturers', 'teams.leader', 'teams.members', 'attendanceLocations'])
            ->firstOrFail();

        // Tambahkan URL scanner QR untuk setiap lokasi kehadiran
        $business->attendanceLocations->each(function ($location) use ($business_unique_id) {
            $location->scanner_url = route('owner.qr_scanner.show', [
                'business_unique_id' => $business_unique_id,
                'slug' => $location->slug,
                'unique_id' => $location->unique_id
            ]);
        });

        // Ambil daftar staff yang bukan lecturer (untuk dropdown tambah staff)
        $staffList = User::whereNotIn('id', $business->lecturers->pluck('id'))->get();

        // Ambil daftar lecturer yang terkait dengan bisnis ini dengan pagination (untuk $lecturerList)
        $lecturerList = $business->lecturers()->paginate(10); // Pagination untuk lecturer yang sudah terdaftar

        // Ambil daftar lecturer yang terkait dengan bisnis ini, namun tanpa pagination (untuk $lecturerListAvailable)
        $lecturerListAvailable = $business->lecturers()->get(); // Tanpa pagination untuk semua lecturer yang terdaftar

        // Fetch search and filter parameters from request untuk teams (fungsi teams tetap sama)
        $search = request('search');
        $sort = request('sort');
        $filter = request('filter');

        // Query the teams with the search and sort functionality (fungsi teams tetap sama)
        $teamsQuery = $business->teams()->with(['leader', 'members']);

        if ($search) {
            $teamsQuery->where('name', 'like', '%' . $search . '%')
                ->orWhere('team_unique_id', 'like', '%' . $search . '%');
        }

        if ($filter) {
            $teamsQuery->where('leader_id', $filter);
        }

        if ($sort) {
            switch ($sort) {
                case 'name_asc':
                    $teamsQuery->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $teamsQuery->orderBy('name', 'desc');
                    break;
                case 'members_asc':
                    $teamsQuery->withCount('members')->orderBy('members_count', 'asc');
                    break;
                case 'members_desc':
                    $teamsQuery->withCount('members')->orderBy('members_count', 'desc');
                    break;
            }
        }

        // Paginate the teams (paginasi teams tetap sama)
        $teams = $teamsQuery->paginate(10);

        // Kirim data ke view, dengan menambahkan kedua daftar lecturer
        return view('owner.businesses.manage', compact('business', 'staffList', 'teams', 'lecturerList', 'lecturerListAvailable'));
    }


    public function addTeam(Request $request, $business_unique_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'leader_id' => 'nullable|exists:users,id',
        ]);

        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        // Generate Unique Team ID
        $teamUniqueId = 'TEAM-' . strtoupper(uniqid());

        // Buat Tim baru
        $team = $business->teams()->create([
            'team_unique_id' => $teamUniqueId,
            'name' => $request->name,
            'leader_id' => $request->leader_id,
        ]);

        // Jika leader_id diberikan, tambahkan leader sebagai member
        if ($request->leader_id) {
            $team->members()->attach($request->leader_id);
        }

        return redirect()->route('owner.businesses.manage', $business_unique_id)->with('success', 'Team created successfully.');
    }

    public function deleteTeam($business_unique_id, $team_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        $team = $business->teams()->findOrFail($team_id);

        // Hapus tim
        $team->delete();

        return redirect()->route('owner.businesses.manage', $business_unique_id)
            ->with('success', 'Team deleted successfully.');
    }

    public function addStaff(Request $request, $business_unique_id)
    {
        $request->validate([
            'staff_id' => 'required|exists:users,id',
        ]);

        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        // Pastikan hanya owner yang bisa menambahkan staff
        if ($business->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah user sudah menjadi staff di business ini
        if ($business->staff()->where('user_id', $request->staff_id)->exists()) {
            return redirect()->route('owner.businesses.manage', $business_unique_id)
                ->with('error', 'User is already a staff member.');
        }

        // Tambahkan user sebagai staff dengan role Lecturer
        $business->addStaff($request->staff_id, 'Lecturer');

        // Ubah role user dari Student ke Lecturer
        $user = User::findOrFail($request->staff_id);
        if ($user->hasRole('Student')) {
            $user->removeRole('Student'); // Hapus role Student
        }
        $user->assignRole('Lecturer'); // Tambahkan role Lecturer

        return redirect()->route('owner.businesses.manage', $business_unique_id)
            ->with('success', 'Staff added successfully and role updated to Lecturer.');
    }


    public function removeStaff($business_unique_id, $staff_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        $user = User::findOrFail($staff_id);

        // Periksa apakah user adalah ketua tim
        if ($business->teams()->where('leader_id', $user->id)->exists()) {
            return redirect()->route('owner.businesses.manage', $business_unique_id)
                ->with('error', 'Cannot remove this user as they are assigned as a team leader.');
        }

        // Hapus user dari business staff
        $business->removeStaff($user->id);

        // Kembalikan role user dari Lecturer ke Student
        if ($user->hasRole('Lecturer')) {
            $user->removeRole('Lecturer'); // Hapus role Lecturer
            $user->assignRole('Student');  // Tambahkan role Student
        }

        return redirect()->route('owner.businesses.manage', $business_unique_id)
            ->with('success', 'Staff removed successfully and role reverted to Student.');
    }

    public function searchLecturers(Request $request)
    {
        $query = $request->input('query');

        // Cari user dengan role 'student' (default) atau 'lecturer'
        $users = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['student', 'lecturer']);
        })
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'avatar']); // Ambil hanya id, name, dan avatar untuk efisiensi

        return response()->json($users);
    }

    public function addManager(Request $request, $business_unique_id, $team_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // 1. Dapatkan Business & Team
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        $team = $business->teams()->findOrFail($team_id);

        // 2. Ambil user yang akan dijadikan manager
        $user = User::findOrFail($request->user_id);

        // 3. Periksa apakah user adalah leader tim ini
        $isLeader = ($team->leader_id === $user->id);

        // 4. Periksa apakah user adalah lecturer atau staff
        $isLecturerOrStaff = $business->lecturers->contains($user->id) || $business->staff->contains($user->id);

        // 5. Gabung logika (boleh jadi manager jika user leader ATAU lecturer/staff)
        if (!$isLeader && !$isLecturerOrStaff) {
            return back()->with('error', 'User is not recognized as staff, lecturer, or leader in this business.');
        }

        // 6. Pastikan user belum jadi manager di tim ini
        if ($team->managers->contains($user->id)) {
            return back()->with('error', 'User is already a manager for this team.');
        }

        // 7. Tambahkan user sebagai manager
        $team->managers()->attach($user->id);

        // 8. Tambahkan user sebagai member jika belum menjadi anggota tim
        if (!$team->members->contains($user->id)) {
            $team->members()->attach($user->id);
        }

        return back()->with('success', 'Manager added successfully and also added as a team member.');
    }

    public function removeManager($businessUniqueId, $teamId, $userId)
    {
        try {
            // 1. Cari business berdasarkan business_unique_id
            $business = Business::where('business_unique_id', $businessUniqueId)->firstOrFail();

            // 2. Cari tim berdasarkan team_id dan business_id
            $team = Team::where('id', $teamId)
                ->where('business_id', $business->id) // Gunakan business_id, bukan business_unique_id
                ->firstOrFail();

            // 3. Hapus relasi manager dari tim
            $team->managers()->detach($userId);

            // 4. Berikan feedback ke user
            return back()->with('success', 'Manager removed successfully.');
        } catch (\Exception $e) {
            // 5. Berikan feedback error ke user
            return back()->with('error', 'Failed to remove manager. Please try again.');
        }
    }

}
