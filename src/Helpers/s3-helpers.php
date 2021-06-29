<?php

use Illuminate\Support\Facades\Storage;
use Sfneal\Helpers\Aws\S3\Utils\S3;

/**
 * Return either an S3 file url.
 *
 * @param string $path
 * @param bool $temp
 * @param DateTimeInterface|null $expiration
 * @return string
 */
function s3FileURL(string $path, bool $temp = true, DateTimeInterface $expiration = null): string
{
    // todo: refactor to `fileUrl()`
    if ($temp) {
        return (new S3($path))->urlTemp($expiration);
    } else {
        return (new S3($path))->url();
    }
}

/**
 * Return a temporary S3 file url.
 *
 * @param string $path
 * @param DateTimeInterface|null $expiration
 * @return string
 */
function s3FileUrlTemp(string $path, DateTimeInterface $expiration = null): string
{
    return (new S3($path))->urlTemp($expiration);
}

/**
 * Determine if an S3 file exists.
 *
 * @param string $s3_key
 * @return bool
 */
function s3Exists(string $s3_key): bool
{
    return Storage::disk('s3')->exists($s3_key);
}
