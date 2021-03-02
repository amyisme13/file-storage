<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessZipFile implements ShouldQueue, ShouldBeUnique
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
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId()
    {
        return $this->file->slug;
    }

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // First download the file
        $downloaded = $this->downloadFileFromS3(
            $this->file->path,
            $this->file->path
        );

        if (!$downloaded) {
            throw new \Exception('Unable to download file');
        }

        // Then unzip and store it to public
        $unzipped = Storage::disk('public')->extractTo(
            "unzipped/{$this->file->slug}",
            Storage::path($this->file->path)
        );

        if (!$unzipped) {
            throw new \Exception('Unable to unzip file');
        }

        // Process finished, delete the downloaded file
        Storage::delete($this->file->path);

        $this->file->processed_at = now();
        $this->file->save();
    }

    /**
     * Download file from s3 and save it to local.
     *
     * @param string $from
     * @param string $to
     * @return bool
     */
    private function downloadFileFromS3($from, $to)
    {
        $fileContent = Storage::disk('s3')->get($from);
        return Storage::put($to, $fileContent);
    }
}
