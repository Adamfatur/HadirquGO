<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLocation;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class AttendanceLocationController extends Controller
{
    public function index($business_unique_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        // Only show locations for this specific business
        $locations = $business->attendanceLocations()->paginate(10);

        // Tambahkan URL scanner QR untuk setiap lokasi kehadiran
        $locations->each(function ($location) use ($business_unique_id) {
            $location->scanner_url = route('owner.qr_scanner.show', [
                'business_unique_id' => $business_unique_id,
                'slug' => $location->slug,
                'unique_id' => $location->unique_id
            ]);
        });

        return view('owner.attendance_locations.index', compact('business', 'locations'));
    }


    public function create($business_unique_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        return view('owner.attendance_locations.create', compact('business'));
    }

    public function store(Request $request, $business_unique_id)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255|unique:attendance_locations,name',
            'description' => 'nullable|string',
        ]);

        $business->attendanceLocations()->create([
            'unique_id' => 'LOC-' . strtoupper(Str::random(6)),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('owner.attendance_locations.index', $business->business_unique_id)->with('success', 'Attendance location added successfully.');
    }

    public function edit($business_unique_id, AttendanceLocation $location)
    {
        $business = Business::where('business_unique_id', $business_unique_id)->firstOrFail();
        return view('owner.attendance_locations.edit', compact('business', 'location'));
    }

    public function update(Request $request, $business_unique_id, AttendanceLocation $location)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attendance_locations,name,' . $location->id,
            'description' => 'nullable|string',
        ]);

        $location->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->route('owner.attendance_locations.index', $business_unique_id)->with('success', 'Attendance location updated successfully.');
    }

    public function destroy($business_unique_id, AttendanceLocation $location)
    {
        $location->delete();
        return redirect()->route('owner.attendance_locations.index', $business_unique_id)->with('success', 'Attendance location deleted successfully.');
    }
}

