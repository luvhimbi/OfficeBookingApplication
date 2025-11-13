<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $messageContent;

    public function __construct($subject, $message)
    {
        $this->subjectLine = $subject;
        $this->messageContent = $message;
    }

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->html($this->messageContent);
    }
}
