<?php

namespace App\Mail;

use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class NotifySubscriberAboutPost extends Mailable
{
    use Queueable, SerializesModels;

    private Post $post;

    private Subscription $subscription;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Post $post, Subscription $subscription)
    {
        $this->post = $post;
        $this->subscription = $subscription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('');
    }
}
