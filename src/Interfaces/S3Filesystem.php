<?php

namespace Sfneal\Helpers\Aws\S3\Interfaces;

use Closure;
use DateTimeInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Response;

interface S3Filesystem
{
    /**
     * Return an S3 file url.
     *
     * @return string
     */
    public function url(): string;

    /**
     * Return a temporary S3 file url.
     *
     * @param DateTimeInterface|null $expiration
     * @return string
     */
    public function urlTemp(DateTimeInterface $expiration = null): string;

    /**
     * Determine if an S3 file exists.
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     * Upload a file to AWS S3.
     *
     * @param string $localFilePath
     * @param string|null $acl
     * @return string
     */
    public function upload(string $localFilePath, string $acl = null): string;

    /**
     * Upload raw file contents to AWS S3.
     *
     * @param string $fileContents
     * @param string|null $acl
     * @return string
     */
    public function upload_raw(string $fileContents, string $acl = null): string;

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
     * Delete a file or folder from an S3 bucket.
     *
     * @return bool
     */
    public function delete(): bool;

    /**
     * List all of the files in an S3 directory & return an array of files with constructed URLs.
     *
     * @return array
     */
    public function list(): array;

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
     * @return array
     */
    public function allFiles(Closure $closure = null): array;

    /**
     * Retrieve an array of all directories within another directory with an optional filtering closure.
     *
     * @param Closure|null $closure
     * @return array
     */
    public function allDirectories(Closure $closure = null): array;
}
