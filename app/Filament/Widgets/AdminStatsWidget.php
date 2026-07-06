<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $today = now()->toDateString();

        $totalEmployees = User::where('role', 'karyawan')->count();

        $presentToday = Attendance::whereDate('date', $today)
            ->where('status', 'Hadir')
            ->count();

        $lateToday = Attendance::whereDate('date', $today)
            ->where('status', 'Terlambat')
            ->count();

        $leaveToday = Attendance::whereDate('date', $today)
            ->whereIn('status', ['Izin', 'Sakit'])
            ->count();

        return [
            Stat::make('Total Karyawan', $totalEmployees)
                ->description('Karyawan aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Hadir Hari Ini', $presentToday)
                ->description('Masuk tepat waktu')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Terlambat Hari Ini', $lateToday)
                ->description('Datang terlambat')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),

            Stat::make('Izin / Sakit Hari Ini', $leaveToday)
                ->description('Tidak masuk kerja')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning'),
        ];
    }
}