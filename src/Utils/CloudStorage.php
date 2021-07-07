<?php


namespace Sfneal\Helpers\Aws\S3\Utils;


use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Sfneal\Helpers\Aws\S3\Utils\Traits\LocalFileDeletion;
use Sfneal\Helpers\Aws\S3\Utils\Traits\UploadStreaming;

class CloudStorage
{
    use LocalFileDeletion;
    use UploadStreaming;

    /**
     * @var string AWS S3 file key
     */
    protected $s3Key;

    /**
     * @var string Storage S3 cloud disk name
     */
    protected $disk;

    /**
     * S3 constructor.
     *
     * @param string $s3Key
     */
    public function __construct(string $s3Key)
    {
        $this->s3Key = $s3Key;
        $this->disk = config('filesystem.cloud', 's3');
        $this->streaming = config('s3-helpers.streaming', true);
    }

    /**
     * Retrieve the S3 key (useful in conjunctions with `autocompletePath()` method).
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->s3Key;
    }

    /**
     * Set the filesystem disk.
     *
     * @param string $disk
     * @return $this
     */
    public function setDisk(string $disk): self
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * Retrieve a Filesystem instance for the specified disk.
     *
     * @return Filesystem|FilesystemAdapter
     */
    protected function storageDisk(): FilesystemAdapter
    {
        return Storage::disk($this->disk);
    }
}
