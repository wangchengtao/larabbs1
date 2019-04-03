<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\User;
use App\Notifications\TopicReplied;
use Illuminate\Support\Facades\Notification;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function created(Reply $reply)
    {
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();

        // 通知话题作者有新的评论
        // $reply->topic->user->notify(new TopicReplied($reply));

        $user_ids = $reply->topic->replies->pluck('user_id');
        Notification::send(User::find($user_ids), new TopicReplied($reply));
    }

    public function updating(Reply $reply)
    {
        //
    }
}
