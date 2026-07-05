<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan Kerja';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TimePicker::make('work_start')
                ->label('Jam Masuk')
                ->required()
                ->seconds(false)
                ->displayFormat('H:i'),

            Forms\Components\TimePicker::make('late_tolerance')
                ->label('Batas Toleransi Terlambat')
                ->required()
                ->seconds(false)
                ->displayFormat('H:i'),

            Forms\Components\TimePicker::make('work_end')
                ->label('Jam Pulang')
                ->required()
                ->seconds(false)
                ->displayFormat('H:i'),

            Forms\Components\Section::make('Pengaturan Jam Kerja')
                ->schema([
                    Forms\Components\TimePicker::make('work_start')
                        ->label('Jam Masuk')
                        ->required()
                        ->seconds(false)
                        ->displayFormat('H:i'),

                    Forms\Components\TimePicker::make('late_tolerance')
                        ->label('Batas Toleransi Terlambat')
                        ->required()
                        ->seconds(false)
                        ->displayFormat('H:i'),

                    Forms\Components\TimePicker::make('work_end')
                        ->label('Jam Pulang')
                        ->required()
                        ->seconds(false)
                        ->displayFormat('H:i'),
                ])
                ->columns(3),

            Forms\Components\Section::make('Pengaturan Lokasi Kantor')
                ->description('Atur koordinat dan radius lokasi absensi')
                ->schema([
                    Forms\Components\TextInput::make('latitude')
                        ->label('Latitude')
                        ->placeholder('-6.200000')
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('longitude')
                        ->label('Longitude')
                        ->placeholder('106.816666')
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('radius')
                        ->label('Radius (meter)')
                        ->numeric()
                        ->default(100)
                        ->required()
                        ->suffix('meter')
                        ->helperText('Jarak maksimal karyawan dari kantor untuk bisa absen'),
                ])
                ->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSettings::route('/'),
        ];
    }
}
