<?php

namespace App\Listeners;

use App\Events\QuoteGeneratedEvent;
use App\Mail\QuoteGeneratedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class QuoteGeneratedListener implements ShouldQueue
{

    public $connection = 'database';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QuoteGeneratedEvent  $event
     * @return void
     */
    public function handle(QuoteGeneratedEvent $event)
    {
        $data = $event->getData();

        Mail::to($data["email"])
            ->send(new QuoteGeneratedMail([
                "startDate" => $data["startDate"],
                "endDate" => $data["endDate"],
                "company" => $data["company"]
            ]));
    }
}
