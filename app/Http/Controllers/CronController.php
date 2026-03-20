<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
        /**
     * Execute daily scheduled tasks triggered by the external cron function.
     *
     * @param  string  $key
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(string $key)
    {
        $cronKey = env('MPEC_CRON_KEY');

        if (empty($cronKey) || $key !== $cronKey) {
            Log::warning('CronController: unauthorized access attempt.', [
                'provided_key' => $key,
                'ip' => request()->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        Log::info('CronController: starting scheduled tasks execution.', [
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        $results = [];

        // Verify and update academic periods (activate/deactivate based on dates)
        Artisan::call('app:verificar-periodos');
        $results['verificar_periodos'] = trim(Artisan::output());

        // Generate final grades for groups whose period ends tomorrow
        Artisan::call('app:generar-notas-finales');
        $results['generar_notas_finales'] = trim(Artisan::output());

        Log::info('CronController: scheduled tasks completed.', ['results' => $results]);

        return response()->json([
            'success' => true,
            'message' => 'Scheduled tasks executed successfully.',
            'timestamp' => now()->toDateTimeString(),
            'results' => $results,
        ]);
    }
}
