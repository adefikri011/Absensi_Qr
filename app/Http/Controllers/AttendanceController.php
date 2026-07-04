<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Support\Str;
use App\Models\QrToken;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;


class AttendanceController extends Controller
{
    public function showQrGenerator()
    {
        return view('attendance.generator');
    }

    public function generateNewToken()
    {
        // Hapus token lama
        QrToken::truncate();

        // Buat token baru
        $token = \Illuminate\Support\Str::random(40);

        // Simpan ke database
        QrToken::create([
            'token' => $token,
            'expires_at' => now()->addSeconds(10),
        ]);

        // Return SVG langsung
        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(250)
            ->margin(1)
            ->generate($token);

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'no-cache, no-store');
    }

    public function processScan(Request $request)
    {
        $token = $request->token;

        $qr = QrToken::where('token', $token)
            ->where('expires_at', '>=', now())
            ->first();

        if (! $qr) {
            return response()->json([
                'message' => 'QR tidak valid atau sudah kadaluarsa '
            ], 400);
        }

        $user = Auth::user();
        $today = now()->toDateString();

        $setting = Setting::first();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (! $attendance) {

            $status = 'Hadir';

            if (now()->format('H:i:s') > $setting->late_tolerance) {
                $status = 'Terlambat';
            }

            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'time_in' => now()->toTimeString(),
                'status' => $status,
            ]);

            return response()->json([
                'message' => 'Check-in berhasil (' . $status . ')'
            ]);
        }

        if ($attendance->time_out === null) {

            $attendance->update([
                'time_out' => now()->toTimeString(),
            ]);

            return response()->json([
                'message' => 'Check-out berhasil '
            ]);
        }

        return response()->json([
            'message' => 'Anda sudah melakukan absensi hari ini'
        ], 400);
    }
}
