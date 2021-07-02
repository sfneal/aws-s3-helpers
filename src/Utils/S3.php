<?php

namespace Sfneal\Helpers\Aws\S3\Utils;

use Closure;
use DateTimeInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Sfneal\Helpers\Aws\S3\Interfaces\S3Filesystem;

class S3 implements S3Filesystem
{
    /**
     * @var string
     */
    private $s3Key;

    /**
     * @var string
     */
    private $disk;

    /**
     * S3 constructor.
     *
     * @param string $s3Key
     */
    public function __construct(string $s3Key)
    {
        $this->s3Key = $s3Key;
        $this->disk = config('filesystem.cloud', 's3');
    }

    /**
     * Retrieve a Filesystem instance for the specified disk.
     *
     * @return Filesystem|FilesystemAdapter
     */
    private function storageDisk(): FilesystemAdapter
    {
        return Storage::disk($this->disk);
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
     * Return either an S3 file url.
     *
     * @return string
     */
    public function url(): string
    {
        return $this->storageDisk()->url($this->s3Key);
    }

    /**
     * Return either a temporary S3 file url.
     *
     * @param DateTimeInterface|null $expiration
     * @return string
     */
    public function urlTemp(DateTimeInterface $expiration = null): string
    {
        return $this->storageDisk()->temporaryUrl($this->s3Key, $expiration ?? now()->addMinutes(60));
    }

    /**
     * Upload a file to an S3 bucket.
     *
     * @param string $localFilePath
     * @param string|null $acl
     * @return self
     */
    public function upload(string $localFilePath, string $acl = null): self
    {
        return $this->uploadRaw(fopen($localFilePath, 'r+'), $acl);
    }

    /**
     * Upload raw file contents to an S3 bucket.
     *
     * @param $fileContents
     * @param string|null $acl
     * @return self
     */
    public function uploadRaw($fileContents, string $acl = null): self
    {
        $this->storageDisk()->put($this->s3Key, $fileContents, $acl);

        return $this;
    }

    /**
     * Download a file from an S3 bucket.
     *
     * @param string|null $fileName
     * @return \Illuminate\Http\Response
     * @throws FileNotFoundException|\League\Flysystem\FileNotFoundException
     */
    public function download(string $fileName = null): \Illuminate\Http\Response
    {
        $response = [
            'Content-Type' => $this->storageDisk()->getMimetype($this->s3Key),
            'Content-Length' => $this->storageDisk()->getSize($this->s3Key),
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename=".$fileName ?? basename($this->s3Key).'"',
            'Content-Transfer-Encoding' => 'binary',
        ];

        return Response::make($this->storageDisk()->get($this->s3Key), 200, $response);
    }

    /**
     * Autocomplete an S3 path by providing the known start of a path.
     *
     * - once path autocompletion is resolved the $s3_key property is replaced with the found path
     *
     * @return $this
     */
    public function autocompletePath(): self
    {
        // Extract the known $base of the path & the $wildcard
        $directory = dirname($this->s3Key);

        // Get all of the folders in the base directory
        $folders = $this->storageDisk()->directories($directory);

        // Filter folders to find the wildcard path
        $folders = array_filter($folders, function ($value) {
            return str_starts_with($value, $this->s3Key);
        });

        // return the resolved path
        $this->s3Key = collect($folders)->values()->first();

        return $this;
    }

    /**
     * Retrieve an array of all files in a directory with an optional filtering closure.
     *
     * @param Closure|null $closure
     * @return array
     */
    public function allFiles(Closure $closure = null): array
    {
        // Create array of all files
        $allFiles = $this->storageDisk()->allFiles($this->s3Key);

        // Apply filtering closure
        if (isset($closure)) {
            return array_filter(array_values($allFiles), $closure);
        }

        return $allFiles;
    }

    /**
     * Retrieve an array of all directories within another directory with an optional filtering closure.
     *
     * @param Closure|null $closure
     * @return array
     */
    public function allDirectories(Closure $closure = null): array
    {
        // Create array of all directories
        $allDirectories = $this->storageDisk()->allDirectories($this->s3Key);

        // Apply filtering closure
        if (isset($closure)) {
            return array_filter(array_values($allDirectories), $closure);
        }

        return $allDirectories;
    }
}
