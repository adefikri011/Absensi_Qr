<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;

class MonthlySummaryWidget extends Widget
{
    protected static string $view = 'filament.widgets.monthly-summary';

    protected int | string | array $columnSpan = 1;

    protected static ?int $sort = 3;

    public function getData(): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $hadir = Attendance::whereBetween('date', [$start, $end])
            ->where('status', 'Hadir')->count();

        $terlambat = Attendance::whereBetween('date', [$start, $end])
            ->where('status', 'Terlambat')->count();

        $izin = Attendance::whereBetween('date', [$start, $end])
            ->where('status', 'Izin')->count();

        $sakit = Attendance::whereBetween('date', [$start, $end])
            ->where('status', 'Sakit')->count();

        $total = $hadir + $terlambat + $izin + $sakit ?: 1;

        $attendanceRate = round((($hadir + $terlambat) / $total) * 100);

        return [
            'rows' => [
                [
                    'label' => 'Hadir',
                    'value' => $hadir,
                    'color' => 'emerald',
                    'icon'  => 'heroicon-o-check-circle',
                ],
                [
                    'label' => 'Terlambat',
                    'value' => $terlambat,
                    'color' => 'rose',
                    'icon'  => 'heroicon-o-clock',
                ],
                [
                    'label' => 'Izin',
                    'value' => $izin,
                    'color' => 'amber',
                    'icon'  => 'heroicon-o-document-text',
                ],
                [
                    'label' => 'Sakit',
                    'value' => $sakit,
                    'color' => 'orange',
                    'icon'  => 'heroicon-o-heart',
                ],
            ],
            'attendanceRate' => $attendanceRate,
        ];
    }
}