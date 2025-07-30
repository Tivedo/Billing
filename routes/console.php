<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
Schedule::command('app:reminding-payment')->daily();
Schedule::command('invoice:generate')->monthlyOn(1, '08:00');
Schedule::command('app:pembatalan')->dailyAt('00:00');
Schedule::command('app:reminding-bupot')->dailyAt('00:00');