<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifySubscriberAboutPost;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubscriptionNotification;

class SendEmailsPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send {subscriber?} {--Q|queue?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send newest posts emails to subscribers.';

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
        if ($this->argument('subscriber')) {
            $this->info($this->argument('subscriber'));
            //$subscriber = Subscription::query()->where('email', '=', $this->argument('subscriber'))->first();
            $user = User::query()->where('email', '=', $this->argument('subscriber'))->first();
            //$user = $subscriber->user()->first();

            //if ($subscriber) {
            if ($user) {
                $posts = Post::query()->where('sent', 0)->get();

                foreach($posts as $post) {
                    //send notification email
                    $this->sendEmailToUser($user, $post->id);
                }

                //collect($posts)->map(function ($post) use($user) {
                    //Mail::to($subscriber->email)->send(new NotifySubscriberAboutPost($post, $subscriber->toArray()));
                //});


                Post::query()->update(['sent' => true]);
                $this->info('emails sent');
            } else {
                $this->error('Subscriber not found');
            }
        } else {
            if ($this->confirm('Do you want to send emails to all users?', true)) {
                //$subscribers = Subscription::all()->toArray();
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

                /*
                $this->info(json_encode($subscribers));
                collect($posts)->map(function (Post $post) use($subscribers) {
                    collect($subscribers)->map(function (Subscription $subscriber) use($post) {
                        Mail::to($subscriber->email)->send(new NotifySubscriberAboutPost($post, $subscriber));
                    });
                });
                */

                $this->info('emails sent');
                Post::query()->update(['sent' => true]);
            } else if ($this->confirm('Do you want to send new posts emails to specific user?', true)) {
                $subscriber = $this->ask('What is the email?');
                //$subscriber = Subscription::query()->where('email', '=', $subscriber)->get();
                $user = User::query()->where('email', '=',$subscriber)->first();

                if ($user) {
                    $posts = Post::query()->where('sent', '=', 0);
                    foreach($posts as $post) {
                        $this->sendEmailToUser($user, $post->id);
                    }
                    /*
                    collect($posts)->map(function (Post $post) use($subscriber) {
                        collect($subscriber)->map(function (Subscription $subscriber) use($post) {
                            Mail::to($subscriber->email)->send(new NotifySubscriberAboutPost($post, $subscriber));
                        });
                    });
                    */
                    Post::query()->update(['sent' => true]);
                    $this->info('emails sent');
                } else {
                    $this->error('Subscriber not found');
                }
            }
        }
        return 0;
    }
}
