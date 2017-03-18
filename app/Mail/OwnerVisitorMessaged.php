<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OwnerVisitorSentMessege extends Mailable
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
     * Messege text.
     *
     * @var string
     */
    public $text;

    /**
     * Create a new message instance.
     *
     * @param  \App\User $receiver
     * @param  object  $sender
     *  string  name
     *  string  email
     *  string  phone
     * @param  string  $page_url
     * @param  string  $text
     * @return void
     */
    public function __construct(User $receiver, $sender, $page_url, $text)
    {
        $this->receiver = $receiver;
        $this->sender = $sender;
        $this->page_url = $page_url;
        $this->text = $text;
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
            ->subject(sprintf('Message from your %s page visitor', config('app.name')))
            ->markdown('emails.owner.visitorSentMessege');
    }
}
