<?php

namespace App\Console\Commands;

use Aws\Exception\AwsException;
use Aws\Laravel\AwsFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MediaConvertGenerateEndpoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mediaconvert:generate-endpoint';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate your account-specific endpoint for AWS Elemental MediaConvert.';

    private const VAR_KEY = 'AWS_MEDIACONVERT_ENDPOINT';

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
    public function handle()
    {
        if ($this->shouldCancelCommand()) {
            $this->comment('Command canceled.');
            return 0;
        }

        try {
            $this->info('Fetching endpoint from AWS');

            /** @var \Aws\MediaConvert\MediaConvertClient $client */
            $client = AwsFacade::createClient('mediaconvert');
            $result = $client->describeEndpoints();
            $endpoint = $result['Endpoints'][0]['Url'];
        } catch (AwsException $err) {
            $this->error($err->getMessage());
            return 1;
        }

        $path = $this->getEnvPath();
        if ($this->envContainsVar()) {
            $replacedEnv = str_replace(
                self::VAR_KEY . '=' . $this->getCurrentEndpoint(),
                self::VAR_KEY . '=' . $endpoint,
                file_get_contents($path)
            );

            file_put_contents($path, $replacedEnv);
        } else {
            file_put_contents(
                $path,
                PHP_EOL . self::VAR_KEY . "=$endpoint" . PHP_EOL,
                FILE_APPEND
            );
        }

        $this->comment("New endpoint: {$endpoint}");
        return 0;
    }

    /**
     * Get current mediaconvert endpoint
     *
     * @return string|null
     */
    private function getCurrentEndpoint()
    {
        return config('services.mediaconvert.endpoint', '');
    }

    /**
     * Check if command should be canceled
     *
     * @return bool
     */
    private function shouldCancelCommand()
    {
        $endpoint = $this->getCurrentEndpoint();
        if ($endpoint === '') {
            return false;
        }

        return !$this->confirmReplace();
    }

    /**
     * Check if user really want to replace.
     *
     * @return bool
     */
    private function confirmReplace()
    {
        return $this->confirm(
            'Endpoint already set. Are you sure you want to replace it?'
        );
    }

    /**
     * Get env path.
     *
     * @return string
     */
    private function getEnvPath()
    {
        return base_path('.env');
    }

    /**
     * Check if .env contains AWS_MEDIACONVERT_ENDPOINT var.
     *
     * @return bool
     */
    private function envContainsVar()
    {
        return Str::contains(
            file_get_contents($this->getEnvPath()),
            self::VAR_KEY
        );
    }
}
