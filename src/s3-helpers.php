<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Response;
use Sfneal\Helpers\Aws\S3\S3;


/**
 * Return either an S3 file url or a local file url
 *
 * @param string $path
 * @param bool $temp
 * @return mixed
 */
function fileURL(string $path, bool $temp = true) {
    return (new S3($path))->url($temp);
}


/**
 * Determine if an S3 file exists
 *
 * @param string $s3_key
 * @return bool
 */
function s3_exists(string $s3_key): bool {
    return (new S3($s3_key))->exists();
}


/**
 * Upload a file to an S3 bucket
 *
 * @param $s3_key
 * @param $file_path
 * @param null $acl
 * @return mixed
 */
function s3_upload($s3_key, $file_path, $acl=null) {
    return (new S3($s3_key))->upload($file_path, $acl);
}


/**
 * Download a file from an S3 bucket
 *
 * @param $file_url
 * @param string|null $file_name
 * @return Response
 * @throws FileNotFoundException
 */
function s3_download($file_url, string $file_name = null): Response {
    return (new S3($file_url))->download($file_name);
}


/**
 * Delete a file or folder from an S3 bucket
 *
 * @param $s3_key
 * @return bool
 */
function s3_delete($s3_key): bool
{
    return (new S3($s3_key))->delete();
}


/**
 * Upload raw file contents to an S3 bucket
 *
 * @param $s3_key
 * @param string $file_contents
 * @param string|null $acl
 * @return mixed
 */
function s3_upload_raw($s3_key, string $file_contents, string $acl = null) {
    return (new S3($s3_key))->upload_raw($file_contents, $acl);
}


/**
 * List all of the files in an S3 directory
 *
 * @param $s3_key
 * @return array
 */
function s3_list($s3_key): array
{
    return (new S3($s3_key))->list();
}
