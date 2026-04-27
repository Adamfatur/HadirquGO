<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\AttendanceToken;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    // Karena tidak ada lagi pemilihan tim, method chooseTeam() dihapus

    public function checkIn()
    {
        // Langsung panggil generateQrCode dengan tipe "checkin"
        return $this->generateQrCode('checkin');
    }

    public function checkOut()
    {
        // Langsung panggil generateQrCode dengan tipe "checkout"
        return $this->generateQrCode('checkout');
    }

    private function generateQrCode($type)
    {
        $user = Auth::user();

        // Ambil token terakhir (yang aktif) untuk dapat previous_location_id
        $lastToken = AttendanceToken::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('expires_at', 'desc')
            ->first();

        // Tentukan previous_location_id jika ada token aktif sebelumnya
        $previousLocationId = $lastToken ? $lastToken->attendance_location_id : null;

        // Periksa apakah ada token aktif untuk tipe yang sama dan belum kadaluwarsa
        $existingToken = AttendanceToken::where('user_id', $user->id)
            ->where('type', $type)
            ->where('is_active', true)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($existingToken) {
            // Gunakan token yang ada jika masih aktif
            $token = $existingToken->token;
            $expiresAt = $existingToken->expires_at;
        } else {
            // Nonaktifkan token lama (type yang sama)
            AttendanceToken::where('user_id', $user->id)
                ->where('type', $type)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Hapus token yang sudah expired
            AttendanceToken::where('user_id', $user->id)
                ->where('expires_at', '<', Carbon::now())
                ->delete();

            // Buat token baru dengan masa berlaku 10 detik
            $token = Str::random(64);
            $expiresAt = Carbon::now()->addSeconds(10); // Durasi diubah menjadi 10 detik

            AttendanceToken::create([
                'user_id'              => $user->id,
                'token'                => $token,
                'type'                 => $type,
                'is_active'            => true,
                'expires_at'           => $expiresAt,
                'attendance_location_id' => $previousLocationId, // Simpan previous_location_id jika ada
            ]);
        }

        // Konversi waktu kedaluwarsa ke zona waktu Jakarta
        $expiresAtJakarta = $expiresAt->copy()->timezone('Asia/Jakarta')->toDateTimeString();

        // Generate QR code dalam bentuk base64
        $qrCode = base64_encode(
            QrCode::format('png')->size(300)->generate($token)
        );

        // Pilih view sesuai jenis checkin/checkout
        $view = $type === 'checkin'
            ? 'student.qrcode.checkin'
            : 'student.qrcode.checkout';

        return view($view, [
            'qrCode'    => $qrCode,
            'expiresAt' => $expiresAtJakarta,
        ]);
    }

    public function checkActiveCheckinToken(Request $request)
    {
        $user = Auth::user();

        // Periksa apakah ada token aktif dengan tipe 'checkin'
        $activeToken = AttendanceToken::where('user_id', $user->id)
            ->where('is_active', 1)
            ->where('type', 'checkin')
            ->first();

        if ($activeToken) {
            // Masih ada token aktif => "active"
            return response()->json([
                'status' => 'active',
                'message' => 'Active checkin token exists.'
            ]);
        }

        // Tidak ada token aktif => "inactive"
        return response()->json([
            'status' => 'inactive',
            'message' => 'No active checkin token found.'
        ]);
    }

    public function checkActiveCheckoutToken(Request $request)
    {
        $user = Auth::user();

        // Periksa apakah ada token aktif dengan tipe 'checkout'
        $activeToken = AttendanceToken::where('user_id', $user->id)
            ->where('is_active', 1)
            ->where('type', 'checkout') // Filter berdasarkan tipe 'checkout'
            ->first();

        if ($activeToken) {
            // Masih ada token aktif => "active"
            return response()->json([
                'status' => 'active',
                'message' => 'Active checkout token exists.'
            ]);
        }

        // Tidak ada token aktif => "inactive"
        return response()->json([
            'status' => 'inactive',
            'message' => 'No active checkout token found.'
        ]);
    }
    
    public function success()
    {
        return view('student.attendance.successssss');
    }
}