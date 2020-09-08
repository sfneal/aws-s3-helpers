<?php

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

/**
 * Return either an S3 file url or a local file url.
 *
 * @param $path
 * @param bool $temp
 * @return mixed
 */
function fileURL($path, $temp = true)
{
    if ($temp) {
        return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(60));
    } else {
        return Storage::disk('s3')->url($path);
    }
}

/**
 * Determine if an S3 file exists.
 *
 * @param string $s3_key
 * @return bool
 */
function s3_exists(string $s3_key)
{
    return Storage::disk('s3')->exists($s3_key);
}

/**
 * Upload a file to an S3 bucket.
 *
 * @param $s3_key
 * @param $file_path
 * @param null $acl
 * @return mixed
 */
function s3_upload($s3_key, $file_path, $acl = null)
{
    if (is_null($acl)) {
        Storage::disk('s3')->put($s3_key, fopen($file_path, 'r+'));
    } else {
        Storage::disk('s3')->put($s3_key, fopen($file_path, 'r+'), $acl);
    }

    return fileURL($s3_key);
}

/**
 * Download a file from an S3 bucket.
 *
 * @param $file_url
 * @param null $file_name
 * @return \Illuminate\Http\Response
 * @throws FileNotFoundException
 */
function s3_download($file_url, $file_name = null)
{
    if (is_null($file_name)) {
        $file_name = basename($file_url);
    }

    $mime = Storage::disk('s3')->getMimetype($file_url);
    $size = Storage::disk('s3')->getSize($file_url);

    $response = [
        'Content-Type' => $mime,
        'Content-Length' => $size,
        'Content-Description' => 'File Transfer',
        'Content-Disposition' => "attachment; filename={$file_name}",
        'Content-Transfer-Encoding' => 'binary',
    ];

    return Response::make(Storage::disk('s3')->get($file_url), 200, $response);
}

/**
 * Delete a file or folder from an S3 bucket.
 *
 * @param $s3_key
 */
function s3_delete($s3_key)
{
    Storage::disk('s3')->delete($s3_key);
}

/**
 * Upload raw file contents to an S3 bucket.
 *
 * @param $s3_key
 * @param $file_contents
 * @param null $acl
 * @return mixed
 */
function s3_upload_raw($s3_key, $file_contents, $acl = null)
{
    if (is_null($acl)) {
        Storage::disk('s3')->put($s3_key, $file_contents);
    } else {
        Storage::disk('s3')->put($s3_key, $file_contents, $acl);
    }

    return fileURL($s3_key);
}

/**
 * List all of the files in an S3 directory.
 *
 * @param $s3_key
 * @return array
 */
function s3_list($s3_key)
{
    $storage = Storage::disk('s3');
    $client = $storage->getAdapter()->getClient();
    $command = $client->getCommand('ListObjects');
    $command['Bucket'] = $storage->getAdapter()->getBucket();
    $command['Prefix'] = $s3_key;
    $result = $client->execute($command);

    $files = [];
    if (isset($result['Contents']) && ! empty($result['Contents'])) {
        foreach ($result['Contents'] as $content) {
            $url = fileURL($content['Key']);
            $parts = explode('/', explode('?', $url, 2)[0]);
            $files[] = [
                'name' => end($parts),
                'url' => $url,
                'key' => $content['Key'],
            ];
        }
    }

    return $files;
}
