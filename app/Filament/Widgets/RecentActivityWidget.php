<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-activity';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 4;

    public function getActivities()
    {
        return Attendance::with('user')
            ->latest()
            ->take(5)
            ->get();
    }
}