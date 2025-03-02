<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:send-event-remiders')
    ->hourly()
    ->appendOutputTo(storage_path('logs/event-reminders.log'))
    ->description('Send notification to all event attendee that event is about to start');
