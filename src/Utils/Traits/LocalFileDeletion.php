<?php


namespace Sfneal\Helpers\Aws\S3\Utils\Traits;


trait LocalFileDeletion
{
    /**
     * @var bool Enable/disable deleting the local file after it's been uploaded
     */
    protected $deleteLocalFileAfterUpload = false;

    /**
     * Enable deleting the local file after it's been uploaded.
     *
     * @return $this
     */
    public function enableDeleteLocalFileAfterUpload(): self
    {
        $this->deleteLocalFileAfterUpload = true;

        return $this;
    }

    /**
     * Delete the local file path if post upload file deletion is enabled.
     *
     * @param string $localFilePath
     */
    protected function deleteLocalFileIfEnabled(string $localFilePath): void
    {
        if ($this->isLocalFileDeletingEnabled()) {
            unlink($localFilePath);
        }
    }

    /**
     * Determine if deleting the local file after it's been uploaded is enabled.
     *
     * @return bool
     */
    private function isLocalFileDeletingEnabled(): bool
    {
        return $this->deleteLocalFileAfterUpload;
    }
}
