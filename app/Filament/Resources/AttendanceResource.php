<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Absensi';

    protected static ?string $pluralModelLabel = 'Absensi';

    protected static ?string $modelLabel = 'Absensi';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Absensi')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Karyawan')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TimePicker::make('time_in')
                            ->label('Jam Masuk')
                            ->seconds(false)
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TimePicker::make('time_out')
                            ->label('Jam Pulang')
                            ->seconds(false)
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('status')
                            ->label('Status Kehadiran')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TimePicker::make('time_out_requested')
                            ->label('Permintaan Pulang Cepat')
                            ->seconds(false)
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('early_checkout_status')
                            ->label('Status Pulang Cepat')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Textarea::make('early_checkout_reason')
                            ->label('Alasan Pulang Cepat')
                            ->rows(4)
                            ->columnSpanFull()
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Karyawan')
                    ->searchable()
                    ->sortable()
                    ->description(fn(Attendance $record) => $record->user?->position?->name ?? 'Tanpa Jabatan'),

                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time_in')
                    ->label('Jam Masuk')
                    ->formatStateUsing(fn($state) => $state ? \Carbon\Carbon::parse($state)->format('H:i') : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time_out')
                    ->label('Jam Pulang')
                    ->formatStateUsing(function ($state, Attendance $record) {
                        if ($state) {
                            return \Carbon\Carbon::parse($state)->format('H:i');
                        }

                        if ($record->early_checkout_status === 'pending' && $record->time_out_requested) {
                            return 'Menunggu approval';
                        }

                        return '-';
                    })
                    ->description(function (Attendance $record) {
                        if ($record->early_checkout_status === 'pending' && $record->time_out_requested) {
                            return 'Request: ' . \Carbon\Carbon::parse($record->time_out_requested)->format('H:i');
                        }

                        return null;
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Terlambat' => 'danger',
                        'Izin' => 'warning',
                        'Sakit' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('early_checkout_status')
                    ->label('Pulang Cepat')
                    ->badge()
                    ->placeholder('Tidak ada request')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        default => 'Tidak ada request',
                    })
                    ->color(fn($state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('early_checkout_reason')
                    ->label('Alasan Pulang Cepat')
                    ->limit(35)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Terlambat' => 'Terlambat',
                        'Izin' => 'Izin',
                        'Sakit' => 'Sakit',
                    ]),

                Tables\Filters\SelectFilter::make('early_checkout_status')
                    ->label('Status Pulang Cepat')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\Filter::make('date_range')
                    ->label('Filter Tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    }),
            ])

            ->headerActions([
                Tables\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('danger')
                    ->form([
                        Forms\Components\Select::make('period')
                            ->label('Periode')
                            ->options([
                                'day' => 'Hari Ini',
                                'all' => 'Semua Data',
                                'week' => 'Minggu Ini',
                                'month' => 'Bulan Ini',
                                'year' => 'Tahun Ini',

                            ])
                            ->default('month')
                            ->required(),
                    ])
                    ->action(function (array $data) {

                        $export = new \App\Exports\AttendanceExport($data['period']);

                        return response()->streamDownload(function () use ($export, $data) {

                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
                                'exports.attendance-pdf',
                                [
                                    'attendances' => $export->getData(),
                                    'period' => $data['period'],
                                ]
                            );

                            echo $pdf->output();
                        }, 'laporan-absensi.pdf');
                    }),
            ])

            ->actions([
                Tables\Actions\Action::make('approve_early_checkout')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Pulang Cepat?')
                    ->modalDescription(
                        fn(Attendance $record) =>
                        "Alasan: " . ($record->early_checkout_reason ?? 'Tidak ada alasan') .
                            "\nWaktu pulang: " . ($record->time_out_requested
                                ? \Carbon\Carbon::parse($record->time_out_requested)->format('H:i')
                                : '-')
                    )
                    ->action(function (Attendance $record) {
                        $record->forceFill([
                            'time_out' => $record->time_out_requested,
                            'early_checkout_status' => 'approved',
                        ])->save();

                        Notification::make()
                            ->title('Pulang cepat disetujui ✅')
                            ->success()
                            ->send();
                    })
                    ->visible(
                        fn(Attendance $record): bool =>
                        $record->early_checkout_status === 'pending'
                    ),

                Tables\Actions\Action::make('reject_early_checkout')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pulang Cepat?')
                    ->modalDescription(
                        fn(Attendance $record) =>
                        "Yakin ingin menolak pengajuan dari " .
                            ($record->user?->name ?? 'karyawan') . "?"
                    )
                    ->action(function (Attendance $record) {
                        $record->forceFill([
                            'early_checkout_status' => 'rejected',
                        ])->save();

                        Notification::make()
                            ->title('Pengajuan pulang cepat ditolak ❌')
                            ->danger()
                            ->send();
                    })
                    ->visible(
                        fn(Attendance $record): bool =>
                        $record->early_checkout_status === 'pending'
                    ),

                // ✅ View Detail
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Karyawan')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nama Karyawan'),

                        TextEntry::make('user.position.name')
                            ->label('Jabatan'),
                    ])
                    ->columns(2),

                Section::make('Detail Absensi')
                    ->schema([
                        TextEntry::make('date')
                            ->label('Tanggal')
                            ->date('d M Y'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'Hadir' => 'success',
                                'Terlambat' => 'danger',
                                'Izin' => 'warning',
                                'Sakit' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('time_in')
                            ->label('Jam Masuk')
                            ->formatStateUsing(
                                fn($state) =>
                                $state ? \Carbon\Carbon::parse($state)->format('H:i') : '-'
                            ),

                        TextEntry::make('time_out')
                            ->label('Jam Pulang')
                            ->formatStateUsing(
                                fn($state) =>
                                $state ? \Carbon\Carbon::parse($state)->format('H:i') : '-'
                            ),
                    ])
                    ->columns(2),

                Section::make('Pulang Cepat')
                    ->schema([
                        TextEntry::make('time_out_requested')
                            ->label('Waktu Pulang Diminta')
                            ->formatStateUsing(
                                fn($state) =>
                                $state ? \Carbon\Carbon::parse($state)->format('H:i') : '-'
                            ),

                        TextEntry::make('early_checkout_status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn($state) => match ($state) {
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                default => '-',
                            })
                            ->color(fn($state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('early_checkout_reason')
                            ->label('Alasan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(
                        fn(Attendance $record) =>
                        $record->early_checkout_status !== null
                    ),
            ]);
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'view' => Pages\ViewAttendance::route('/{record}'),
        ];
    }
}
