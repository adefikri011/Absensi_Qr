<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Pengajuan Izin';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('user.name')
                ->label('Karyawan')
                ->disabled(),

            Forms\Components\TextInput::make('type')
                ->label('Jenis')
                ->disabled(),

            Forms\Components\TextInput::make('date')
                ->label('Tanggal')
                ->disabled(),

            Forms\Components\Textarea::make('reason')
                ->label('Alasan')
                ->disabled(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
                ->required(),

            Forms\Components\Textarea::make('note')
                ->label('Catatan Admin')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Karyawan')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis')
                    ->colors([
                        'primary' => 'izin',
                        'danger' => 'sakit',
                    ]),

                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reason')
                    ->label('Alasan')
                    ->limit(30),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->action(function (LeaveRequest $record) {
                        $record->update(['status' => 'approved']);

                        Attendance::firstOrCreate(
                            [
                                'user_id' => $record->user_id,
                                'date' => $record->date,
                            ],
                            [
                                'time_in' => '00:00:00',
                                'status' => ucfirst($record->type),
                            ]
                        );

                        Notification::make()
                            ->title('Pengajuan disetujui ✅')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (LeaveRequest $record) => $record->status === 'pending'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->requiresConfirmation()
                    ->action(function (LeaveRequest $record) {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->title('Pengajuan ditolak ❌')
                            ->danger()
                            ->send();
                    })
                    ->visible(fn (LeaveRequest $record) => $record->status === 'pending'),

                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveRequests::route('/'),
            'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }
}