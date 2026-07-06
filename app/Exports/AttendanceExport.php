<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function collection()
    {
        $query = Attendance::with('user');

        switch ($this->period) {

            case 'week':
                $query->whereBetween('date', [
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                ]);
                break;

            case 'month':
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
                break;

            case 'year':
                $query->whereYear('date', now()->year);
                break;

            case 'all':
            default:
                // semua data
                break;
        }

        return $query->orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->user->name ?? '-',
            Carbon::parse($attendance->date)->format('d-m-Y'),
            $attendance->time_in
                ? Carbon::parse($attendance->time_in)->format('H:i')
                : '-',
            $attendance->time_out
                ? Carbon::parse($attendance->time_out)->format('H:i')
                : '-',
            $attendance->status,
        ];
    }
}