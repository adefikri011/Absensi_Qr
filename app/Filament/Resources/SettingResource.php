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
