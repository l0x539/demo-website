<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubscriptionNotification;

class ServerSendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server:sendEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send newest posts emails to all subscribers';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    private function sendEmailToUser($user, $postId)
    {
        $details = [
            'greeting' => 'Subscription reminder',
            'body' => 'Hello, '.$user->name.'!',
            'actionText' => 'Visit My Subscription',
            'actionUrl' => env('APP_URL').'/api/posts/'.$postId,
            'lastLine' => 'Thank you for choosing our service',
        ];
        Notification::send($user, new SubscriptionNotification($details));
    }

    public function handle()
    {
        //$subscribers = Subscription::all();

        $posts = Post::query()->where('sent', '=', 0)->get();

        foreach($posts as $post) {
            $website = $post->website()->first();
            $subscribers = $website->subscribers()->get();

            foreach($subscribers as $subscriber) {
                $user = $subscriber->user()->first();
                $this->sendEmailToUser($user, $post->id);
            }
        }

        $this->info('emails sent');
        Post::query()->update(['sent' => true]);
        return 0;
    }
}
