<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdministradorDashboard extends Component
{
    public $user;
    public function mount()
    {
        $this->user = Auth::user();
    }
    public function render()
    {
        return view('livewire.admin.administrador-dashboard');
    }
}
