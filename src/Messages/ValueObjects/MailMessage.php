<?php

namespace Amir\Notifier\Messages\ValueObjects;

use Illuminate\Mail\Mailable;

class MailMessage extends Mailable implements NotifiableMessage
{
    public function __construct(public $subject, public $messageText ,public $view)
    {
        $this->subject($this->subject)
            ->view($this->view);
    }

    public function getMessage(): array
    {
        return [
            'subject' =>  $this->subject,
            'message' => $this->messageText,
            'view' => $this->view
        ];
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view($this->view);
    }
}