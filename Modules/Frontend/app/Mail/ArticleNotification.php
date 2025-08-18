<?php

namespace Modules\Frontend\Mail;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ArticleNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $article;
    public $subscriber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Article $article, $subscriber)
    {
        $this->article = $article;
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $encryptedEmail = encryptDecryptId($this->subscriber->email, 'encrypt');
        return $this->markdown('frontend::frontend.emails.article_notification')
        ->subject(__('frontend::message.new_article') . ': ' . $this->article->title)
        ->with([
            'article' => $this->article,
            'subscriber' => $this->subscriber,
        ]);
    }
}
