<?php

namespace App\Listeners;

use App\Models\SnsNotification;

class HandleSnsNotification
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $notification = new SnsNotification();
        $notification->message_id = $event->payload['MessageId'];
        $notification->message = $event->payload['Message'];
        $notification->topic_arn = $event->payload['TopicArn'];
        $notification->message_timestamp = $event->payload['Timestamp'];
        $notification->raw = json_encode($event->payload);
        $notification->save();
    }
}
