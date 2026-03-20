<?php

use App\Console\Commands\VerificarPeriodos;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule as FacadesSchedule;


FacadesSchedule::command('app:verificar-periodos')->daily();
