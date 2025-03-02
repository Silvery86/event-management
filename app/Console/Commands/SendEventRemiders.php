<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventRemiders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-remiders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to all event attendee that event is about to start';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::with('attendees.user')
        ->whereBetween('start_time', [now(), now()->addDay()])
        ->get();

        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);
        $this->info('Sending notification to ' . $eventCount . ' ' . $eventLabel);

        $events->each(function ($event) {
            $event->attendees->each(function ($attendee) use ($event) {
                $attendee->user->notify(
                    new EventReminderNotification($event)
                );
            });
        });
        $this->info('Successfully sent notification to ' . $eventCount . ' ' . $eventLabel);
    }
}
