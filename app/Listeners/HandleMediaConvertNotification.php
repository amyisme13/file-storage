<?php

namespace App\Listeners;

use App\Models\File;
use App\Models\MediaConvertJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Rennokki\LaravelSnsEvents\Events\SnsNotification;

class HandleMediaConvertNotification
{
    /**
     * Check if the given message is a MediaConvert message
     *
     * @param mixed $message
     * @return bool
     */
    private function isMediaConvertMessage($message)
    {
        return is_array($message) &&
            $message['detail-type'] === 'MediaConvert Job State Change';
    }

    /**
     * Return path from the given S3 URI
     *
     * @param string $uri
     * @return void
     */
    private function toPathFromS3Uri($uri)
    {
        $bucket = config('filesystems.disks.s3.bucket');
        $rootPath = Storage::disk('s3')->path('');

        return str_replace("s3://{$bucket}/{$rootPath}", '', $uri);
    }

    /**
     * Handle the event.
     *
     * @param  \Rennokki\LaravelSnsEvents\Events\SnsNotification  $event
     * @return void
     */
    public function handle(SnsNotification $event)
    {
        $message = json_decode($event->payload['message']['Message'], true);
        if (!$this->isMediaConvertMessage($message)) {
            Log::debug(
                'Notification received but not mediaconvert ' .
                    $message['detail-type']
            );
            return;
        }

        $jobId = $message['detail']['jobId'];
        $job = MediaConvertJob::where('job_id', $jobId)->first();
        if (is_null($job)) {
            Log::debug("Unable to find job with job_id {$jobId}");
            return;
        }

        $job->status = $message['detail']['status'];
        $job->save();

        $file = File::where('job_id', $jobId)->first();
        if (is_null($file)) {
            Log::debug("Unable to find file with job_id {$jobId}");
            return;
        }

        $file->processed_at = now();
        $file->video_path = $this->toPathFromS3Uri(
            $message['detail']['outputGroupDetails'][0]['playlistFilePaths'][0]
        );
        $file->thumbnail = $this->toPathFromS3Uri(
            $message['detail']['outputGroupDetails'][1]['outputDetails'][0][
                'outputFilePaths'
            ][0]
        );
        $file->save();
    }
}
