<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\AttendanceToken;
use App\Models\Attendance;
use App\Models\EmailLog;
use App\Models\UserPoint;
use App\Models\UserPointSummary; // Import Model UserPointSummary
use App\Models\AttendanceLocation;
use App\Mail\CheckinMail;
use App\Mail\CheckoutMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Import DB untuk transaksi
use Carbon\Carbon;
//model notification
use App\Models\Notification;

class QrScannerController extends Controller
{
    /**
     * Menampilkan QR Scanner
     *
     * @param string $business_unique_id
     * @param string $slug
     * @param string $unique_id
     * @return \Illuminate\View\View
     */
    public function showScanner($business_unique_id, $slug, $unique_id)
    {
        $location = AttendanceLocation::where('unique_id', $unique_id)
            ->where('slug', $slug)
            ->firstOrFail();

        Log::info("Showing QR scanner for location", [
            'business_unique_id' => $business_unique_id,
            'slug' => $slug,
            'unique_id' => $unique_id,
            'location_name' => $location->name
        ]);

        return view('owner.qr_scanner.show', compact('location'));
    }

    public function showOutIn($business_unique_id, $slug, $unique_id)
    {
        // Fetch the location based on unique_id and slug
        $location = AttendanceLocation::where('unique_id', $unique_id)
            ->where('slug', $slug)
            ->firstOrFail();

        // Log information about the location and the action
        Log::info("Showing checkout-checkin scanner for location", [
            'business_unique_id' => $business_unique_id,
            'slug' => $slug,
            'unique_id' => $unique_id,
            'location_name' => $location->name
        ]);

        // Pass the location to the view
        return view('owner.qr_scanner.showoutin', compact('location'));
    }

    /**
     * Memproses Attendance (Check-in atau Check-out)
     *
     * @param \Illuminate\Http\Request $request
     * @param int $location_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function processAttendance(Request $request, $location_id)
    {
        // Mulai transaksi database untuk memastikan semua operasi berhasil atau gagal bersama
        DB::beginTransaction();

        try {
            // Ambil data QR code dari request
            $qrCodeMessage = $request->input('qrCode');

            // Cari AttendanceToken berdasarkan token
            $attendanceToken = AttendanceToken::where('token', $qrCodeMessage)->first();

            // Validasi token
            if (!$attendanceToken) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid token.'
                ], 400);
            }

            if (!$attendanceToken->is_active || $attendanceToken->isExpired()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Token is inactive or expired.'
                ], 400);
            }

            // Ambil user dari token
            $user = $attendanceToken->user;

            // Validasi lokasi
            $location = AttendanceLocation::find($location_id);
            if (!$location) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid location.'
                ], 400);
            }

            $now  = Carbon::now();
            $type = $attendanceToken->type; // 'checkin' atau 'checkout'

            // Tentukan alur: Check-in atau Check-out
            if ($type === 'checkin') {
                $response = $this->handleCheckin($user, $location, $attendanceToken, $now);
            } elseif ($type === 'checkout') {
                $response = $this->handleCheckout($user, $location, $attendanceToken, $now);
            } else {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid token type.'
                ], 400);
            }

            // Jika sampai sini tidak ada exception, commit transaksi
            DB::commit();

            return $response;
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            Log::error('Error processing attendance: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }

    /**
     * Fungsi privat untuk menangani logic Check-in
     *
     * @param \App\Models\User $user
     * @param \App\Models\AttendanceLocation $location
     * @param \App\Models\AttendanceToken $attendanceToken
     * @param \Carbon\Carbon $now
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Fungsi privat untuk menangani logic Check-in
     */
    private function handleCheckin($user, $location, $attendanceToken, $now)
    {
        $locationId = $location->id;

        // Cari checkout terakhir (jika ada)
        $lastCheckout = Attendance::where('user_id', $user->id)
            ->where('type', 'checkout')
            ->orderBy('created_at', 'desc')
            ->first();

        // Ambil semua sesi aktif di hari ini (setelah checkout terakhir)
        $activeSession = Attendance::where('user_id', $user->id)
            ->where('created_at', '>', $lastCheckout->created_at ?? '1970-01-01')
            ->whereDate('checkin_time', $now->toDateString()) // <-- hanya hari ini
            ->where(function ($query) {
                $query->where('is_active', true)
                    ->orWhereNull('is_active');
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $lastAttendance = $activeSession->last();

        // Jika sudah check-in di lokasi yang sama & masih aktif, kembalikan error
        if ($lastAttendance
            && $lastAttendance->attendance_location_id == $locationId
            && $lastAttendance->is_active
        ) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You are already checked in at this location.'
            ], 400);
        }

        $locations = [];
        if ($lastAttendance) {
            // User pindah ke lokasi baru di hari yang sama
            $durationAtLocation = $lastAttendance->checkin_time->diffInMinutes($now);

            $lastAttendance->duration_at_location = $durationAtLocation;
            $lastAttendance->is_active            = false;
            $lastAttendance->save();

            // Perbarui daftar lokasi session
            $locations = $lastAttendance->locations ?? [];
            $locations[] = $locationId;
        } else {
            // Mulai session baru
            $locations = [$locationId];
        }

        // Buat record Attendance baru (check-in)
        $attendance = new Attendance();
        $attendance->user_id                = $user->id;
        $attendance->attendance_location_id = $locationId;
        $attendance->checkin_time           = $now;
        $attendance->is_active              = true;
        $attendance->type                   = 'checkin';
        $attendance->points                 = 1; // Poin saat check-in
        $attendance->locations              = $locations;
        $attendance->save();

        // Update/buat UserPointSummary (tambah 1 poin check-in)
        $userPointSummary = UserPointSummary::firstOrCreate(
            ['user_id' => $user->id],
            ['total_points' => 0, 'current_points' => 0]
        );
        $userPointSummary->increment('total_points', 1);
        $userPointSummary->increment('current_points', 1);

        // Simpan UserPoint (riwayat poin)
        UserPoint::create([
            'user_id'     => $user->id,
            'points'      => 1,
            'description' => 'Check-in at ' . $location->name,
        ]);

        // Simpan notifikasi
        $notificationMessage = $user->name . ' checked in at ' . $location->name
            . ' at ' . $now->format('H:i:s');
        Notification::create([
            'user_id'                 => $user->id,
            'attendance_location_id'  => $locationId,
            'type'                    => 'checkin',
            'message'                 => $notificationMessage,
            'time'                    => $now,
        ]);

        // Nonaktifkan token
        $attendanceToken->deactivate();

        // Log
        Log::info('User ID ' . $user->id . ' checked in at location ID ' . $locationId);

        // Kirim Email Check-in
        try {
            $attendanceRecord = Attendance::where('user_id', $user->id)
                ->whereDate('created_at', now()->toDateString())
                ->latest('created_at')
                ->first();

            $attendanceId = $attendanceRecord ? $attendanceRecord->id : (Attendance::max('id') + 1);

            Mail::to($user->email)->send(
                new CheckinMail($user, $location, $now, $attendanceId)
            );

            EmailLog::create([
                'recipient' => $user->email,
                'status'    => 'sent',
                'sent_at'   => now(),
            ]);
        } catch (\Exception $e) {
            EmailLog::create([
                'recipient' => $user->email,
                'status'    => 'failed',
                'sent_at'   => now(),
            ]);
            Log::error('Failed to send check-in email to ' . $user->email . ': ' . $e->getMessage());
        }

        return response()->json([
            'status'      => 'checked_in',
            'message'     => 'Check-in successful!',
            'user_name'   => $user->name,
            'user_avatar' => $user->avatar,
        ]);
    }

    /**
     * Fungsi privat untuk menangani logic Check-out
     */
    private function handleCheckout($user, $location, $attendanceToken, $now)
    {
        $locationId = $location->id;

        // Cari checkout terakhir
        $lastCheckout = Attendance::where('user_id', $user->id)
            ->where('type', 'checkout')
            ->orderBy('created_at', 'desc')
            ->first();

        // Hanya ambil attendance di hari ini (setelah checkout terakhir)
        $sessionAttendances = Attendance::where('user_id', $user->id)
            ->where('created_at', '>', $lastCheckout->created_at ?? '1970-01-01')
            ->whereDate('checkin_time', $now->toDateString())  // <-- hanya hari ini
            ->orderBy('created_at', 'asc')
            ->get();

        if ($sessionAttendances->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No active session found to checkout.'
            ], 400);
        }

        $lastAttendance = $sessionAttendances->last();

        // Pastikan checkout di hari yang sama
        if ($lastAttendance->checkin_time->toDateString() !== $now->toDateString()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You can only check out on the same day as your check-in. Please check in again.'
            ], 400);
        }

        // Jika masih aktif, hitung durasi & tutup
        if ($lastAttendance->is_active) {
            $durationAtLocation                     = $lastAttendance->checkin_time->diffInMinutes($now);
            $lastAttendance->duration_at_location   = $durationAtLocation;
            $lastAttendance->checkout_time          = $now;
            $lastAttendance->is_active              = false;
            $lastAttendance->type                   = 'checkout';
            $lastAttendance->save();
        }

        // Total durasi seluruh lokasi dalam sesi (hari) ini
        $totalSessionDuration = $sessionAttendances->sum('duration_at_location');

        // Poin = total durasi
        $pointsEarned = $totalSessionDuration;

        // Update total_daily_duration
        $lastAttendance->total_daily_duration = $totalSessionDuration;
        $lastAttendance->save();

        // Update atau buat UserPointSummary
        $userPointSummary = UserPointSummary::firstOrCreate(
            ['user_id' => $user->id],
            ['total_points' => 0, 'current_points' => 0]
        );
        // Tambah poin
        $userPointSummary->increment('total_points', $pointsEarned);
        $userPointSummary->increment('current_points', $pointsEarned);

        // Simpan UserPoint
        UserPoint::create([
            'user_id'     => $user->id,
            'points'      => $pointsEarned,
            'description' => 'Check-out at ' . $location->name
                . '. Total session duration: ' . $totalSessionDuration . ' minutes.',
        ]);

        // Notifikasi
        $notificationMessage = $user->name . ' checked out from ' . $location->name
            . ' at ' . $now->format('H:i:s') . '. Total session duration: '
            . $totalSessionDuration . ' minutes.';

        Notification::create([
            'user_id' => $user->id,
            'attendance_location_id' => $locationId,
            'type' => 'checkout',
            'message' => $notificationMessage,
            'time' => $now,
        ]);

        // Nonaktifkan token
        $attendanceToken->deactivate();

        // Log
        Log::info('User ID ' . $user->id . ' checked out from location ID ' . $locationId
            . '. Total session duration: ' . $totalSessionDuration . ' minutes.');

        // Kirim Email Check-out
        try {
            $sessionDuration = $totalSessionDuration;
            $attendanceId    = $lastAttendance->id;

            Mail::to($user->email)->send(
                new CheckoutMail($user, $location, $now, $sessionDuration, $attendanceId, $pointsEarned)
            );

            EmailLog::create([
                'recipient' => $user->email,
                'status'    => 'sent',
                'sent_at'   => now(),
            ]);
        } catch (\Exception $e) {
            EmailLog::create([
                'recipient' => $user->email,
                'status'    => 'failed',
                'sent_at'   => now(),
            ]);
            Log::error('Failed to send check-out email to ' . $user->email . ': ' . $e->getMessage());
        }

        return response()->json([
            'status'       => 'checked_out',
            'message'      => 'Check-out successful! Total session duration: '
                . $totalSessionDuration . ' minutes. Points earned: ' . $pointsEarned,
            'user_name'    => $user->name,
            'user_avatar'  => $user->avatar,
            'points_earned'=> $pointsEarned,
        ]);
    }

    /**
     * Menampilkan Form Login Scanner
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('owner.qr_scanner.login_form');
    }

    /**
     * Proses Login via Unique ID
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginViaUniqueId(Request $request)
    {
        $request->validate([
            'unique_id' => 'required|string'
        ]);

        $uniqueIdInput = $request->input('unique_id');

        // Cek data di tabel attendance_locations
        $location = AttendanceLocation::where('unique_id', $uniqueIdInput)->first();

        if (!$location) {
            return back()->withErrors(['invalid_id' => 'Unique ID Not Found.']);
        }

        // Simpan informasi ke session
        $request->session()->put('qr_scanner_auth', $uniqueIdInput);

        Log::info('User logged in to scanner via unique_id: ' . $uniqueIdInput);

        // Arahkan ke halaman scanner
        return redirect()->route('qr_scanner.show_by_unique_id', ['unique_id' => $uniqueIdInput]);
    }

    /**
     * Menampilkan QR Scanner berdasarkan Unique ID
     *
     * @param \Illuminate\Http\Request $request
     * @param string $unique_id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showScannerByUniqueId(Request $request, $unique_id)
    {
        // Pastikan user sudah 'login' via session
        $uniqueIdFromSession = $request->session()->get('qr_scanner_auth');
        if (!$uniqueIdFromSession || $uniqueIdFromSession !== $unique_id) {
            return redirect()->route('qr_scanner.login_form')
                ->withErrors(['unauthorized' => 'Mohon login untuk mengakses scanner.']);
        }

        // Cari location berdasarkan unique_id
        $location = AttendanceLocation::where('unique_id', $unique_id)->firstOrFail();

        Log::info("Showing QR scanner for location [via unique_id login]", [
            'unique_id' => $unique_id,
            'location_name' => $location->name
        ]);

        return view('owner.qr_scanner.show', compact('location'));
    }


    public function processCheckoutCheckin(Request $request, $location_id)
    {
        // Mulai transaksi database agar semua operasi bersifat atomik
        DB::beginTransaction();

        try {
            // Ambil data QR code dari request
            $qrCodeMessage = $request->input('qrCode');

            // Cari AttendanceToken berdasarkan token
            $attendanceToken = AttendanceToken::where('token', $qrCodeMessage)->first();

            // Validasi token
            if (!$attendanceToken) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid token.'
                ], 400);
            }

            if (!$attendanceToken->is_active || $attendanceToken->isExpired()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Token is inactive or expired.'
                ], 400);
            }

            // Ambil user dari token
            $user = $attendanceToken->user;

            // Validasi lokasi
            $location = AttendanceLocation::find($location_id);
            if (!$location) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Invalid location.'
                ], 400);
            }

            $now = Carbon::now();
            $locationId = $location->id;

            /*
             * Jika ada record check-in aktif hari ini, maka lakukan checkout.
             * Total durasi harian dihitung sebagai selisih antara checkin pertama hari itu dengan waktu checkout.
             */
            $lastCheckin = Attendance::where('user_id', $user->id)
                ->where('type', 'checkin')
                ->whereDate('checkin_time', $now->toDateString())
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastCheckin) {
                // Ambil record check-in pertama hari ini
                $firstCheckin = Attendance::where('user_id', $user->id)
                    ->where('type', 'checkin')
                    ->whereDate('checkin_time', $now->toDateString())
                    ->orderBy('checkin_time', 'asc')
                    ->first();

                // Hitung total durasi harian dari checkin pertama hingga checkout sekarang
                $totalDailyDuration = $now->diffInMinutes($firstCheckin->checkin_time);

                // Update record check-in aktif menjadi record checkout
                $lastCheckin->checkout_time = $now;
                $lastCheckin->duration_at_location = $totalDailyDuration; // Sinkron dengan total durasi dari checkin pertama
                $lastCheckin->total_daily_duration = $totalDailyDuration;
                $lastCheckin->is_active = false;
                $lastCheckin->type = 'checkout';
                $lastCheckin->save();

                // Peroleh poin dari total durasi harian
                $pointsEarned = $totalDailyDuration;

                // Perbarui UserPointSummary
                $userPointSummary = UserPointSummary::firstOrCreate(
                    ['user_id' => $user->id],
                    ['total_points' => 0, 'current_points' => 0]
                );
                $userPointSummary->increment('total_points', $pointsEarned);
                $userPointSummary->increment('current_points', $pointsEarned);

                // Simpan riwayat poin untuk checkout
                UserPoint::create([
                    'user_id'     => $user->id,
                    'points'      => $pointsEarned,
                    'description' => 'Checked out from ' . $location->name . '. Total daily duration: ' . $totalDailyDuration . ' minutes.',
                ]);

                // Buat notifikasi checkout terlebih dahulu
                $checkoutNotificationMessage = $user->name . ' checked out from ' . $location->name
                    . ' at ' . $now->format('H:i:s') . '. Total daily duration: ' . $totalDailyDuration . ' minutes.';
                Notification::create([
                    'user_id'                 => $user->id,
                    'attendance_location_id'  => $locationId,
                    'type'                    => 'checkout',
                    'message'                 => $checkoutNotificationMessage,
                    'time'                    => $now,
                ]);
            }

            /*
             * Lakukan proses check-in.
             * (Baik ada proses checkout sebelumnya atau tidak, proses check-in baru tetap dilakukan.)
             */
            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->attendance_location_id = $locationId;
            $attendance->checkin_time = $now;
            $attendance->is_active = true;
            $attendance->type = 'checkin';
            $attendance->points = 1; // Poin tetap untuk check-in
            $attendance->save();

            // Perbarui atau buat UserPointSummary untuk penambahan poin check-in
            $userPointSummary = UserPointSummary::firstOrCreate(
                ['user_id' => $user->id],
                ['total_points' => 0, 'current_points' => 0]
            );
            $userPointSummary->increment('total_points', 1);
            $userPointSummary->increment('current_points', 1);

            // Simpan riwayat poin untuk check-in
            UserPoint::create([
                'user_id'     => $user->id,
                'points'      => 1,
                'description' => 'Checked in at ' . $location->name,
            ]);

            // Buat notifikasi check-in (dibuat setelah notifikasi checkout)
            $checkinNotificationMessage = $user->name . ' checked in at ' . $location->name
                . ' at ' . $now->format('H:i:s');
            Notification::create([
                'user_id'                 => $user->id,
                'attendance_location_id'  => $locationId,
                'type'                    => 'checkin',
                'message'                 => $checkinNotificationMessage,
                'time'                    => $now,
            ]);

            // Nonaktifkan token agar tidak bisa digunakan kembali
            $attendanceToken->deactivate();

            // Log aktivitas
            Log::info('User ID ' . $user->id . ' checked out and checked in at location ID ' . $locationId);

            // Commit transaksi jika tidak ada error
            DB::commit();

            return response()->json([
                'status'      => 'checked_in_checked_out',
                'message'     => 'Checkout and check-in successful!',
                'user_name'   => $user->name,
                'user_avatar' => $user->avatar,
            ]);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            Log::error('Error processing checkout-checkin: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while processing your request.'
            ], 500);
        }
    }
}