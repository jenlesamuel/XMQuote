<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteGeneratedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("jenlesamuel@gmail.com")
            ->subject($this->data["company"])
            ->view('emails.quote')
            ->with([
                "startDate" => $this->data["startDate"],
                "endDate" => $this->data["endDate"]
            ]);
    }
}
