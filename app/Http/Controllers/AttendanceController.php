<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\QrToken;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceController extends Controller
{
    public function showQrGenerator()
    {
        return view('attendance.generator');
    }

    public function generateNewToken()
    {
        QrToken::truncate();

        $token = \Illuminate\Support\Str::random(40);

        QrToken::create([
            'token' => $token,
            'expires_at' => now()->addSeconds(10),
        ]);

        $qrCode = QrCode::format('svg')
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

        // ✅ Validasi token QR
        $qr = QrToken::where('token', $token)
            ->where('expires_at', '>=', now())
            ->first();

        if (! $qr) {
            return response()->json([
                'success' => false,
                'message' => 'QR tidak valid atau sudah kadaluarsa ❌',
            ], 400);
        }

        $user = auth()->user();
        $today = now()->toDateString();
        $setting = Setting::first();

        if (! $setting) {
            return response()->json([
                'success' => false,
                'message' => 'Pengaturan jam kerja belum diatur oleh admin.',
            ], 500);
        }

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // ✅ BELUM ABSEN → CHECK IN
        if (! $attendance) {
            $now = now();
            $nowTime = $now->format('H:i:s');

            // Parse menggunakan Carbon agar perbandingan akurat
            $workStart = Carbon::createFromFormat('H:i:s', $setting->work_start);
            $lateTolerance = Carbon::createFromFormat('H:i:s', $setting->late_tolerance);
            $nowCarbon = Carbon::createFromFormat('H:i:s', $nowTime);

            if ($nowCarbon->lte($workStart)) {
                $status = 'Hadir';
                $appreciation = "Wah, kamu rajin sekali hari ini, {$user->name}! 🌟 Pertahankan!";
            } elseif ($nowCarbon->lte($lateTolerance)) {
                $status = 'Hadir';
                $appreciation = "Tepat waktu! Semangat terus, {$user->name}! 💪";
            } else {
                $status = 'Terlambat';
                $appreciation = "Kamu terlambat nih, {$user->name}. Besok lebih awal ya! ⏰";
            }

            Attendance::create([
                'user_id' => $user->id,
                'date' => $today,
                'time_in' => $nowTime,
                'status' => $status,
            ]);

            return response()->json([
                'success' => true,
                'type' => 'checkin',
                'status' => $status,
                'message' => "Check-in berhasil! ({$status})",
                'appreciation' => $appreciation,
                'name' => $user->name,
                'time' => $now->format('H:i'),
            ]);
        }

        // ✅ SUDAH CHECK IN → CHECK OUT
        if ($attendance->time_out === null) {
            $now = now();
            $nowTime = $now->format('H:i:s');

            $workEnd = Carbon::createFromFormat('H:i:s', $setting->work_end);
            $nowCarbon = Carbon::createFromFormat('H:i:s', $nowTime);

            // Belum waktunya pulang → Early checkout
            if ($nowCarbon->lt($workEnd)) {
                return response()->json([
                    'success' => false,
                    'type' => 'early_checkout',
                    'message' => 'Belum waktunya pulang!',
                    'work_end' => $workEnd->format('H:i'),
                    'current_time' => $now->format('H:i'),
                    'time_out_requested' => $nowTime,
                ]);
            }

            $attendance->update([
                'time_out' => $nowTime,
            ]);

            return response()->json([
                'success' => true,
                'type' => 'checkout',
                'message' => "Check-out berhasil! 🎉",
                'appreciation' => "Kerja keras hari ini, {$user->name}! Istirahat yang baik ya! 🌙",
                'name' => $user->name,
                'time' => $now->format('H:i'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Kamu sudah melakukan absensi hari ini',
        ]);
    }

    public function processEarlyCheckout(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:10',
            'time_out_requested' => 'required',
        ]);

        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', $today)
            ->first();

        if (! $attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Data absensi tidak ditemukan.',
            ], 404);
        }

        if ($attendance->time_out !== null) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah melakukan check-out.',
            ], 400);
        }

        if ($attendance->early_checkout_status === 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan pulang cepat kamu masih menunggu persetujuan admin.',
            ], 400);
        }

        $attendance->forceFill([
            'time_out_requested' => $request->time_out_requested,
            'early_checkout_reason' => $request->reason,
            'early_checkout_status' => 'pending',
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan pulang cepat berhasil dikirim , Menunggu persetujuan admin.',
        ]);
    }
}