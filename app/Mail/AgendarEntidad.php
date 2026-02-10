<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AgendarEntidad extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $subject = 'Nueva agenda';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->markdown('plantillas.agendarentidad')
                ->with('data', $this->data);
        // $this->withSwiftMessage(function ($message) {
        //     $message->getHeaders()
        //         ->addTextHeader('Custom-Header', 'HeaderValue');
        // })->addPart($vcal, 'text/calendar');
    }
}
