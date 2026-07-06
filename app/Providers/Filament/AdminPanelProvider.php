<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\AdminStatsWidget;
use App\Filament\Widgets\AttendanceChartWidget;
use App\Filament\Widgets\AttendanceDistributionWidget;
use App\Filament\Widgets\MonthlySummaryWidget;
use App\Filament\Widgets\RecentActivityWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ── Identitas Panel ──────────────────────────────
            ->default()
            ->id('admin')
            ->path('admin')
            ->authGuard('web')

            // ── Tema ──────────────────────────────────────────
            ->colors([
                'primary' => Color::Amber,
            ])

            // ── Auto Discovery ───────────────────────────────
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources',
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages',
            )

            // ── Widgets Dashboard ────────────────────────────
            // Didaftarkan manual (bukan auto-discover) agar urutan
            // & layout dashboard terkontrol penuh.
            ->widgets([
                AdminStatsWidget::class,
                AttendanceChartWidget::class,
                AttendanceDistributionWidget::class,
                MonthlySummaryWidget::class,
                RecentActivityWidget::class,
            ])

            // ── Middleware ───────────────────────────────────
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}