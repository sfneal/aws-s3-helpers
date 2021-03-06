<?php

namespace Sfneal\Helpers\Aws\S3\Utils;

use Closure;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\File;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Sfneal\Helpers\Aws\S3\Utils\Interfaces\S3Actions;
use Sfneal\Helpers\Aws\S3\Utils\Traits\LocalFileDeletion;
use Sfneal\Helpers\Aws\S3\Utils\Traits\UploadStreaming;

class S3 extends CloudStorage implements S3Actions
{
    use LocalFileDeletion;
    use UploadStreaming;

    /**
     * Upload a file to S3 using automatic streaming or raw file uploading.
     *
     * @param string $localFilePath
     * @param string|null $acl
     * @return CloudStorage
     */
    public function upload(string $localFilePath, string $acl = null): CloudStorage
    {
        // Use streaming for improved performance if enabled
        if ($this->isStreamingEnabled()) {
            $cloudStorage = $this->uploadStream($localFilePath, $acl);
        }

        // Use standard file uploading
        else {
            $cloudStorage = $this->uploadRaw(fopen($localFilePath, 'r+'), $acl);
        }

        // Conditionally delete the local file
        $this->deleteLocalFileIfEnabled($localFilePath);

        return $cloudStorage;
    }

    /**
     * Upload raw file contents to an S3 bucket.
     *
     * @param $fileContents
     * @param string|null $acl
     * @return CloudStorage
     */
    public function uploadRaw($fileContents, string $acl = null): CloudStorage
    {
        $this->storageDisk()->put($this->s3Key, $fileContents, $acl);

        return new CloudStorage($this->s3Key, $this->disk);
    }

    /**
     * Upload a file to S3 using automatic streaming.
     *
     * @param string $localFilePath
     * @param string|null $acl
     * @return CloudStorage
     */
    protected function uploadStream(string $localFilePath, string $acl = null): CloudStorage
    {
        // Upload the file
        $this->storageDisk()->putFileAs(
            dirname($this->s3Key),
            new File($localFilePath),
            basename($this->s3Key),
            $acl
        );

        return new CloudStorage($this->s3Key, $this->disk);
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
