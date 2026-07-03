<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\QrToken;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
}
