<?php

namespace Sfneal\Helpers\Aws\S3\Interfaces;

use Closure;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Sfneal\Helpers\Aws\S3\Utils\CloudStorage;

interface S3Actions
{
    /**
     * Upload a file to S3 using automatic streaming.
     *
     * @param string $localFilePath
     * @param string|null $acl
     * @return CloudStorage
     */
    public function upload(string $localFilePath, string $acl = null): CloudStorage;

    /**
     * Upload raw file contents to AWS S3.
     *
     * @param $fileContents
     * @param string|null $acl
     * @return CloudStorage
     */
    public function uploadRaw($fileContents, string $acl = null): CloudStorage;

    /**
     * Download a file from AWS S3.
     *
     * @param string|null $fileName
     * @return Response
     * @throws FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function download(string $fileName = null): Response;

    /**
     * Autocomplete an S3 path by providing the known start of a path.
     *
     * - once path autocompletion is resolved the $s3_key property is replaced with the found path
     *
     * @return $this
     */
    public function autocompletePath(): self;

    /**
     * Retrieve an array of all files in a directory with an optional filtering closure.
     *
     * @param Closure|null $closure
     * @return Collection
     */
    public function allFiles(Closure $closure = null): Collection;

    /**
     * Retrieve an array of all directories within another directory with an optional filtering closure.
     *
     * @param Closure|null $closure
     * @return Collection
     */
    public function allDirectories(Closure $closure = null): Collection;
}
