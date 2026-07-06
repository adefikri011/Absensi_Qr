<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;

class TodayStatusChart extends Widget
{
    protected static string $view = 'filament.widgets.today-status-summary';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function getData(): array
    {
        $today = now()->toDateString();

        $hadir = Attendance::whereDate('date', $today)
            ->where('status', 'Hadir')
            ->count();

        $terlambat = Attendance::whereDate('date', $today)
            ->where('status', 'Terlambat')
            ->count();

        $izin = Attendance::whereDate('date', $today)
            ->where('status', 'Izin')
            ->count();

        $sakit = Attendance::whereDate('date', $today)
            ->where('status', 'Sakit')
            ->count();

        $total = $hadir + $terlambat + $izin + $sakit ?: 1;

        return compact('hadir', 'terlambat', 'izin', 'sakit', 'total');
    }
}