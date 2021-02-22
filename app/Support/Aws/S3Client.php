<?php

namespace App\Support\Aws;

use Illuminate\Support\Facades\Storage;

class S3Client
{
    /**
     * @var string
     */
    private $disk = 's3';

    /**
     * Create an instance of S3Client
     *
     * @return \App\Support\Aws\S3Client
     */
    public static function create()
    {
        return new S3Client();
    }

    /**
     * Set the disk used to get the adapter & client.
     *
     * @param string $disk
     * @return $this
     */
    public function setAdapter($disk)
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Get a presigned url for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array $options
     * @param string $commandName
     * @return string
     */
    public function getPresignedUrl(
        $path,
        $expiration,
        $options,
        $commandName = 'GetObject'
    ) {
        /** @var \League\Flysystem\AwsS3v3\AwsS3Adapter $adapter */
        $adapter = Storage::disk($this->disk)->getAdapter();

        $client = $adapter->getClient();
        $command = $client->getCommand(
            $commandName,
            array_merge(
                [
                    'Bucket' => $adapter->getBucket(),
                    'Key' => $adapter->getPathPrefix() . $path,
                ],
                $options
            )
        );

        return (string) $client
            ->createPresignedRequest($command, $expiration)
            ->getUri();
    }
}
