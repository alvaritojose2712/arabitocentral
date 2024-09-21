<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class enviarCierre extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$from1,$fromname,$subject)
    {
        $this->data = $data;
        $this->from1 = $from1;
        $this->fromname = $fromname;
        $this->subject = $subject;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->from1, $this->fromname)
        ->subject($this->subject)
        ->view('reportes.cierregeneral',$this->data);
    }
}
