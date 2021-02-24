<?php

namespace App\Support\Aws;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class MediaConvertJob implements Arrayable
{
    /**
     * @var string
     */
    private $input;

    /**
     * Set $input class property.
     *
     * @param string $path must exists
     * @return $this
     * @throws \RuntimeException
     */
    public function setInput($path)
    {
        if (!Storage::disk('s3')->exists($path)) {
            throw new RuntimeException('The path given doesn\'t exists');
        }

        $this->input = $path;

        return $this;
    }

    /**
     * Get the S3 URI for the given path;
     *
     * @param string $path
     * @return string
     */
    private function toS3Uri($path)
    {
        $bucket = config('filesystems.disks.s3.bucket');
        $realPath = Storage::disk('s3')->path($path);

        return "s3://{$bucket}/{$realPath}";
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $inputPath = $this->toS3Uri($this->input);
        $videoDestination = $this->toS3Uri('videos/');
        $thumbnailDestination = $this->toS3Uri('thumbnails/');

        $json = Storage::get('job-template.json');
        $template = json_decode($json, true);
        $template['Settings']['Inputs'][0]['FileInput'] = $inputPath;

        // I know its ugly. Deal with it.
        $template['Settings']['OutputGroups'][0]['OutputGroupSettings'][
            'HlsGroupSettings'
        ]['Destination'] = $videoDestination;
        $template['Settings']['OutputGroups'][1]['OutputGroupSettings'][
            'FileGroupSettings'
        ]['Destination'] = $thumbnailDestination;

        return $template;
    }
}
