<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OwnerVisitorShownAttendanceInterest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * This email receiver instance.
     *
     * @var \App\User
     */
    public $receiver;

    /**
     * This email sender instance.
     *
     * @var mixed
     */
    public $sender;

    /**
     * Group public url.
     *
     * @var string
     */
    public $page_url;

    /**
     * Create a new message instance.
     *
     * @param  \App\User $receiver
     * @param  object  $sender
     *  string  name
     *  string  email
     *  string  phone
     * @param  string  $page_url
     * @return void
     */
    public function __construct(User $receiver, $sender, $page_url)
    {
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->page_url = $page_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->sender->email) {
            $this->replyTo($this->sender->email, $this->sender->name);
        }

        return $this
            ->subject('Wish to begin attending your class')
            ->markdown('emails.owner.visitorShownAttendanceInterest');
    }
}
