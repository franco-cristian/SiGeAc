<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting; // <-- Crearemos este modelo
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EnrollmentSettings extends Component
{
    public $enrollment_start_date;
    public $enrollment_end_date;

    public function mount()
    {
        $this->enrollment_start_date = Setting::where('key', 'enrollment_start_date')->first()?->value;
        $this->enrollment_end_date = Setting::where('key', 'enrollment_end_date')->first()?->value;
    }

    public function saveSettings()
    {
        $this->validate([
            'enrollment_start_date' => 'required|date',
            'enrollment_end_date' => 'required|date|after_or_equal:enrollment_start_date',
        ]);

        // Usamos updateOrCreate para crear la configuración si no existe, o actualizarla si ya existe.
        Setting::updateOrCreate(['key' => 'enrollment_start_date'], ['value' => $this->enrollment_start_date]);
        Setting::updateOrCreate(['key' => 'enrollment_end_date'], ['value' => $this->enrollment_end_date]);

        $this->dispatch('show-toast', ['message' => 'Período de inscripción actualizado con éxito.']);
    }

    public function render()
    {
        return view('livewire.admin.settings.enrollment-settings');
    }
}