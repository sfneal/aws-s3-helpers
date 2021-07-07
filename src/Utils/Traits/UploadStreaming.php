<?php


namespace Sfneal\Helpers\Aws\S3\Utils\Traits;


trait UploadStreaming
{
    /**
     * @var bool Enable/disable upload & download streaming
     */
    protected $streaming;

    /**
     * Enable upload/download streaming regardless of config setting.
     *
     * @return $this
     */
    public function enableStreaming(): self
    {
        $this->streaming = true;

        return $this;
    }

    /**
     * Disable upload/download streaming regardless of config setting.
     *
     * @return $this
     */
    public function disableStreaming(): self
    {
        $this->streaming = false;

        return $this;
    }

    /**
     * Determine if upload/download streaming is enabled.
     *
     * @return bool
     */
    protected function isStreamingEnabled(): bool
    {
        return $this->streaming;
    }
}
