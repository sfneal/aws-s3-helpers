<?php

namespace Sfneal\Helpers\Aws\S3;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class S3
{
    /**
     * @var string
     */
    private $s3_key;

    /**
     * S3 constructor.
     *
     * @param string $s3_key
     */
    public function __construct(string $s3_key)
    {
        $this->s3_key = $s3_key;
    }

    /**
     * Return either an S3 file url or a local file url.
     *
     * @param bool $temp
     * @return string
     */
    public function url(bool $temp = true): string
    {
        if ($temp) {
            return Storage::disk('s3')->temporaryUrl($this->s3_key, now()->addMinutes(60));
        } else {
            return Storage::disk('s3')->url($this->s3_key);
        }
    }

    /**
     * Determine if an S3 file exists.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return Storage::disk('s3')->exists($this->s3_key);
    }

    /**
     * Upload a file to an S3 bucket.
     *
     * @param string $file_path
     * @param string|null $acl
     * @return string
     */
    public function upload(string $file_path, string $acl = null): string
    {
        if (is_null($acl)) {
            Storage::disk('s3')->put($this->s3_key, fopen($file_path, 'r+'));
        } else {
            Storage::disk('s3')->put($this->s3_key, fopen($file_path, 'r+'), $acl);
        }

        return $this->url();
    }

    /**
     * Upload raw file contents to an S3 bucket.
     *
     * @param string $file_contents
     * @param string|null $acl
     * @return string
     */
    public function upload_raw(string $file_contents, string $acl = null): string
    {
        if (is_null($acl)) {
            Storage::disk('s3')->put($this->s3_key, $file_contents);
        } else {
            Storage::disk('s3')->put($this->s3_key, $file_contents, $acl);
        }

        return $this->url();
    }

    /**
     * Download a file from an S3 bucket.
     *
     * @param string|null $file_name
     * @return \Illuminate\Http\Response
     * @throws FileNotFoundException|\League\Flysystem\FileNotFoundException
     */
    public function download(string $file_name = null): \Illuminate\Http\Response
    {
        if (is_null($file_name)) {
            $file_name = basename($this->s3_key);
        }

        $mime = Storage::disk('s3')->getMimetype($this->s3_key);
        $size = Storage::disk('s3')->getSize($this->s3_key);

        $response = [
            'Content-Type' => $mime,
            'Content-Length' => $size,
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$file_name}",
            'Content-Transfer-Encoding' => 'binary',
        ];

        return Response::make(Storage::disk('s3')->get($this->s3_key), 200, $response);
    }

    /**
     * Delete a file or folder from an S3 bucket.
     *
     * @return bool
     */
    public function delete(): bool
    {
        return Storage::disk('s3')->delete($this->s3_key);
    }

    /**
     * List all of the files in an S3 directory.
     *
     * @return array
     */
    public function list(): array
    {
        $storage = Storage::disk('s3');
        $client = $storage->getAdapter()->getClient();
        $command = $client->getCommand('ListObjects');
        $command['Bucket'] = $storage->getAdapter()->getBucket();
        $command['Prefix'] = $this->s3_key;
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
}
