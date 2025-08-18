<?php

namespace Modules\Frontend\Jobs;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Frontend\Mail\ArticleNotification;
use Modules\Frontend\Models\MailSubscribe;
use Illuminate\Support\Facades\Log;

class SendPostNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $article;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Post  $post
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $subscribers = MailSubscribe::where('is_subscribe', 1)->get();

            foreach ($subscribers as $subscriber) {
                Mail::to($subscriber->email)->send(new ArticleNotification($this->article, $subscriber));
            }

        } catch (\Exception $e) {
            // Log::error('Failed to send post notification: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());

        }
    }
}
