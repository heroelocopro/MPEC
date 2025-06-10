<?php

namespace App\Livewire\Colegio;

use App\Models\Colegio;
use App\Models\Grado;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ColegioGrados extends Component
{
    public $modalCreacion = false;
    public function render()
    {
        $colegio = Colegio::where('user_id','=',Auth::user()->id)->first();
        $grados = Grado::where('colegio_id','=',$colegio->id)->get();
        return view('livewire.colegio.colegio-grados', compact('grados','colegio'));
    }
}
