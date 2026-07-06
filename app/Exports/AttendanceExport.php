<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceExport
{
    protected string $period;

    public function __construct(string $period)
    {
        $this->period = $period;
    }

    public function getData()
    {
        $query = Attendance::with('user');

        switch ($this->period) {

            case 'day':
                // Hari ini
                $query->whereDate('date', now()->toDateString());
                break;

            case 'week':
                // 7 hari terakhir
                $query->whereBetween('date', [
                    now()->subDays(6)->toDateString(),
                    now()->toDateString(),
                ]);
                break;

            case 'month':
                // 30 hari terakhir
                $query->whereBetween('date', [
                    now()->subDays(29)->toDateString(),
                    now()->toDateString(),
                ]);
                break;

            case 'year':
                // 365 hari terakhir
                $query->whereBetween('date', [
                    now()->subDays(364)->toDateString(),
                    now()->toDateString(),
                ]);
                break;

            case 'all':
            default:
                // Semua data
                break;
        }

        $data = $query->orderBy('date', 'desc')->get();

        // ✅ MODE DETAIL (PER HARI)
        if ($this->period === 'day') {
            return $data;
        }

        // ✅ MODE SUMMARY (GROUP PER KARYAWAN)
        return $data
            ->groupBy('user_id')
            ->map(function ($items) {

                $user = $items->first()->user;

                return [
                    'name' => $user->name ?? '-',
                    'hadir' => $items->where('status', 'Hadir')->count(),
                    'terlambat' => $items->where('status', 'Terlambat')->count(),
                    'izin' => $items->where('status', 'Izin')->count(),
                    'sakit' => $items->where('status', 'Sakit')->count(),
                ];
            })
            ->values(); 
    }
}