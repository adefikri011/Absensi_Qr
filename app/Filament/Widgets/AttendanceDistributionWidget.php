<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;

class AttendanceDistributionWidget extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Kehadiran';

    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected static ?string $maxHeight = '260px';

    public function getDescription(): ?string
    {
        return 'Proporsi status kehadiran bulan ini';
    }

    protected function getData(): array
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

        return [
            'datasets' => [
                [
                    'data' => [$hadir, $terlambat, $izin, $sakit],
                    'backgroundColor' => [
                        '#10b981', // emerald - hadir
                        '#f43f5e', // rose - terlambat
                        '#f59e0b', // amber - izin
                        '#fb923c', // orange - sakit
                    ],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 3,
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => ['Hadir', 'Terlambat', 'Izin', 'Sakit'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'cutout' => '70%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'boxWidth' => 8,
                        'padding' => 16,
                        'font' => ['size' => 12],
                        'color' => '#64748b',
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => '#0f172a',
                    'padding' => 10,
                    'cornerRadius' => 8,
                    'bodyFont' => ['size' => 12],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}