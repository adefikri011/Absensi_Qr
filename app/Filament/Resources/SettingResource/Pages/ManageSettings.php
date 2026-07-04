<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use Filament\Resources\Pages\Page;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;


class ManageSettings extends Page implements HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected static string $view = 'filament.resources.setting-resource.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::first();

        if (! $setting) {
            $setting = Setting::create([]);
        }

        $this->form->fill($setting->toArray());
    }

    public function form(Form $form): Form
    {
        return SettingResource::form($form)
            ->statePath('data')
            ->model(Setting::first());
    }

    public function save()
    {
        $setting = Setting::first();

        $setting->update($this->form->getState());

        Notification::make()
            ->title('Pengaturan berhasil disimpan ')
            ->success()
            ->send();
    }
}
