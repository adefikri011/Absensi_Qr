<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;

class TopPerformerWidget extends Widget
{
    protected static string $view = 'filament.widgets.top-performer-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 4;

    public function getTopEmployees()
    {
        return Attendance::selectRaw('user_id, COUNT(*) as total')
            ->whereIn('status', ['Hadir'])
            ->whereDate('date', '>=', now()->subDays(7))
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->with('user')
            ->limit(5)
            ->get();
    }
}