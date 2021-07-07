<?php

namespace Sfneal\Helpers\Aws\S3\Utils;

use Closure;
use DateTimeInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Sfneal\Helpers\Aws\S3\Interfaces\S3Filesystem;

class S3 extends CloudStorage implements S3Filesystem
{
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
        return $this->storageDisk()->temporaryUrl(
            $this->s3Key,
            $expiration ?? config('s3-helpers.expiration')
        );
    }

    /**
     * Upload a file to S3 using automatic streaming or raw file uploading.
     *
     * @param string $localFilePath
     * @param string|null $acl
     * @return self
     */
    public function upload(string $localFilePath, string $acl = null): self
    {
        // Use streaming for improved performance if enabled
        if ($this->isStreamingEnabled()) {
            return $this->uploadStream($localFilePath, $acl);
        }

        // Use standard file uploading
        else {
            return $this->uploadRaw(fopen($localFilePath, 'r+'), $acl);
        }
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
     * Upload a file to S3 using automatic streaming.
     *
     * @param string $localFilePath
     * @param string|null $acl
     * @return $this
     */
    protected function uploadStream(string $localFilePath, string $acl = null): self
    {
        $this->storageDisk()->putFileAs(
            dirname($this->s3Key),
            new File($localFilePath),
            basename($this->s3Key),
            $acl
        );

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
        $fileName = $fileName ?? basename($this->s3Key);

        return Response::make(
            $this->storageDisk()->get($this->s3Key),
            200,
            [
                'Content-Type' => $this->storageDisk()->getMimetype($this->s3Key),
                'Content-Length' => $this->storageDisk()->getSize($this->s3Key),
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => "attachment; filename={$fileName}",
                'Content-Transfer-Encoding' => 'binary',
            ]
        );
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
     * @return Collection
     */
    public function allFiles(Closure $closure = null): Collection
    {
        // Create array of all files
        $allFiles = collect($this->storageDisk()->allFiles($this->s3Key));

        // Apply filtering closure
        if (isset($closure)) {
            return $allFiles->filter($closure);
        }

        return $allFiles;
    }

    /**
     * Retrieve an array of all directories within another directory with an optional filtering closure.
     *
     * @param Closure|null $closure
     * @return Collection
     */
    public function allDirectories(Closure $closure = null): Collection
    {
        // Create array of all directories
        $allDirectories = collect($this->storageDisk()->allDirectories($this->s3Key));

        // Apply filtering closure
        if (isset($closure)) {
            return $allDirectories->filter($closure);
        }

        return $allDirectories;
    }
}
