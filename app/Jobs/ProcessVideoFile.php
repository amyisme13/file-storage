<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\MediaConvertJob as ModelsMediaConvertJob;
use App\Support\Aws\MediaConvertJob;
use Aws\Laravel\AwsFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessVideoFile implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 3600;

    /**
     * @var \App\Models\File
     */
    public $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->file->slug;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $args = (new MediaConvertJob())->setInput($this->file->path)->toArray();

        $result = AwsFacade::createClient('mediaconvert', [
            'endpoint' => config('services.mediaconvert.endpoint'),
        ])->createJob($args);

        $job = new ModelsMediaConvertJob();
        $job->job_id = $result['Job']['Id'];
        $job->arn = $result['Job']['Arn'];
        $job->queue = $result['Job']['Queue'];
        $job->status = $result['Job']['Status'];
        $job->raw = json_encode($result->toArray());
        $job->save();

        $this->file->job_id = $result['Job']['Id'];
        $this->file->save();
    }
}
