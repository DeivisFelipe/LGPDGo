<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Verificação de prazos DSAR - executa a cada 6 horas
Schedule::command('dsar:check-deadlines')
    ->everySixHours()
    ->withoutOverlapping()
    ->onSuccess(function () {
        info('✓ Verificação de prazos DSAR concluída com sucesso.');
    })
    ->onFailure(function () {
        logger()->error('✗ Falha na verificação de prazos DSAR.');
    });
