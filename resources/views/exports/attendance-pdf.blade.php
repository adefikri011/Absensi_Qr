<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border: 1px solid #ddd; padding: 6px; }
        table th { background: #f3f4f6; }
    </style>
</head>
<body>

<h2>Laporan Absensi</h2>

<p>
    Periode:
    @if($period === 'day')
        Hari Ini
    @elseif($period === 'week')
        Minggu Ini
    @elseif($period === 'month')
        Bulan Ini
    @elseif($period === 'year')
        Tahun Ini
    @else
        Semua Data
    @endif
</p>

@if($period === 'day')

    {{-- ✅ MODE DETAIL --}}
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}</td>
                    <td>{{ $attendance->time_in ?? '-' }}</td>
                    <td>{{ $attendance->time_out ?? '-' }}</td>
                    <td>{{ $attendance->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@else

    {{-- ✅ MODE SUMMARY --}}
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Total Hadir</th>
                <th>Total Terlambat</th>
                <th>Total Izin</th>
                <th>Total Sakit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td>{{ $row['hadir'] }}</td>
                    <td>{{ $row['terlambat'] }}</td>
                    <td>{{ $row['izin'] }}</td>
                    <td>{{ $row['sakit'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endif

</body>
</html>