<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Menampilkan daftar tim yang ditugaskan kepada Lecturer
     */
    public function index()
    {
        $lecturer = Auth::user();

        // Retrieve the teams that the lecturer leads with eager loading
        $teamsLed = $lecturer->teamsLed()
            ->with(['members', 'business.staff.user', 'business.owner', 'managers'])
            ->get() ?? collect();

        // Retrieve the teams where the lecturer is a member or manager with eager loading
        $teamsJoined = $lecturer->teamsJoined()
            ->with(['leader', 'managers', 'members', 'business.staff.user'])
            ->get() ?? collect();

        return view('lecturer.teams.index', compact('teamsLed', 'teamsJoined'));
    }

    public function addMember(Request $request, $teamId)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $team = Team::findOrFail($teamId);
        $lecturerId = Auth::id();

        if ($request->user_id == $lecturerId) {
            return redirect()->route('lecturer.teams.index')
                ->with('error', 'You cannot add yourself as a team member.');
        }

        if ($team->members->contains($request->user_id)) {
            return redirect()->route('lecturer.teams.index')
                ->with('error', 'This member is already part of the team.');
        }

        $team->members()->attach($request->user_id);

        return redirect()->route('lecturer.teams.index')->with('success', 'Member added successfully.');
    }

    public function updateTeamName(Request $request, $teamId)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $team = Team::findOrFail($teamId);

        if ($team->leader_id !== Auth::id()) {
            return redirect()->route('lecturer.teams.index')->with('error', 'Unauthorized action.');
        }

        $team->update(['name' => $request->name]);
        return redirect()->route('lecturer.teams.index')->with('success', 'Team name updated successfully.');
    }

    public function removeMember($teamId, $userId)
    {
        $team = Team::findOrFail($teamId);
        $lecturerId = Auth::id();

        if ($team->leader_id !== $lecturerId && !$team->managers->contains($lecturerId)) {
            return redirect()->route('lecturer.teams.index')->with('error', 'Unauthorized action.');
        }

        $team->members()->detach($userId);

        return redirect()->route('lecturer.teams.index')->with('success', 'Member removed successfully.');
    }

    public function transferLeader(Request $request, $teamId)
    {
        $request->validate(['new_leader_id' => 'required|exists:users,id']);

        $team = Team::findOrFail($teamId);
        $lecturerId = Auth::id();

        if ($team->leader_id !== $lecturerId) {
            return redirect()->route('lecturer.teams.index')->with('error', 'Unauthorized action.');
        }

        $newLeader = Staff::where('user_id', $request->new_leader_id)
            ->where('business_id', $team->business_id)
            ->firstOrFail();

        if ($newLeader->business_id !== $team->business_id) {
            return redirect()->route('lecturer.teams.index')->with('error', 'The new leader must be a staff member of this business.');
        }

        $team->update(['leader_id' => $newLeader->user_id]);

        if (!$team->members->contains($newLeader->user_id)) {
            $team->members()->attach($newLeader->user_id);
        }

        return redirect()->route('lecturer.teams.index')->with('success', 'Leadership transferred successfully.');
    }

    public function searchMembers(Request $request)
    {
        $query = $request->input('query');

        $members = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'avatar']);

        return response()->json($members);
    }

    public function dissolveTeam($teamId)
    {
        $team = Team::with('business')->findOrFail($teamId);

        // Business owner or team leader can dissolve
        if ($team->business->owner_id !== Auth::id() && $team->leader_id !== Auth::id()) {
            return redirect()->route('lecturer.teams.index')
                ->with('error', 'Only the business owner or team leader can dissolve this team.');
        }

        $teamName = $team->name;
        $team->delete();

        return redirect()->route('lecturer.teams.index')
            ->with('success', "Team \"{$teamName}\" has been dissolved successfully.");
    }
}
