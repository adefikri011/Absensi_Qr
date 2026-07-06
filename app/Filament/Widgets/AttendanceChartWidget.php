<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;

class AttendanceChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Absensi 7 Hari Terakhir';

    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 2; // lebih lebar dari widget ringkasan
    protected static ?string $maxHeight = '260px';

    public function getDescription(): ?string
    {
        return 'Tren kehadiran & keterlambatan karyawan dalam 7 hari terakhir';
    }

    protected function getData(): array
    {
        $hadirData = [];
        $terlambatData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            $hadirData[] = Attendance::whereDate('date', $date)
                ->where('status', 'Hadir')
                ->count();

            $terlambatData[] = Attendance::whereDate('date', $date)
                ->where('status', 'Terlambat')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => $hadirData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.12)',
                    'pointBackgroundColor' => '#10b981',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'borderWidth' => 2.5,
                    'fill' => true,
                    'tension' => 0.45,
                ],
                [
                    'label' => 'Terlambat',
                    'data' => $terlambatData,
                    'borderColor' => '#f43f5e',
                    'backgroundColor' => 'rgba(244, 63, 94, 0.12)',
                    'pointBackgroundColor' => '#f43f5e',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'borderWidth' => 2.5,
                    'fill' => true,
                    'tension' => 0.45,
                ],
            ],
            'labels' => collect(range(6, 0))
                ->map(fn ($i) => now()->subDays($i)->format('d M'))
                ->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'align' => 'center',
                    'labels' => [
                        'usePointStyle' => true,
                        'pointStyle' => 'circle',
                        'boxWidth' => 8,
                        'padding' => 20,
                        'font' => [
                            'size' => 12,
                        ],
                        'color' => '#64748b',
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => '#0f172a',
                    'padding' => 10,
                    'cornerRadius' => 8,
                    'titleFont' => ['size' => 12, 'weight' => '600'],
                    'bodyFont' => ['size' => 12],
                    'displayColors' => true,
                    'boxPadding' => 4,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => '#f1f5f9',
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'precision' => 0,
                        'color' => '#94a3b8',
                        'font' => ['size' => 11],
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'color' => '#94a3b8',
                        'font' => ['size' => 11],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}