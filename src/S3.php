<?php

namespace Sfneal\Helpers\Aws\S3;

use Closure;
use DateTimeInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class S3
{
    /**
     * @var string
     */
    private $s3_key;

    /**
     * @var string
     */
    private $disk;

    /**
     * S3 constructor.
     *
     * @param string $s3_key
     */
    public function __construct(string $s3_key)
    {
        $this->s3_key = $s3_key;
        $this->disk = config('filesystem.cloud', 's3');
    }

    /**
     * Retrieve a Filesystem instance for the specified disk.
     *
     * @return Filesystem
     */
    private function storageDisk(): Filesystem
    {
        return Storage::disk($this->disk);
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
     * @param bool $temp
     * @param DateTimeInterface|null $expiration
     * @return string
     */
    public function url(bool $temp = true, DateTimeInterface $expiration = null): string
    {
        if ($temp) {
            return $this->storageDisk()->temporaryUrl($this->s3_key, $expiration ?? now()->addMinutes(60));
        } else {
            return $this->storageDisk()->url($this->s3_key);
        }
    }

    /**
     * Return either a temporary S3 file url.
     *
     * @param DateTimeInterface|null $expiration
     * @return string
     */
    public function urlTemp(DateTimeInterface $expiration = null): string
    {
        return $this->url(true, $expiration);
    }

    /**
     * Determine if an S3 file exists.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->storageDisk()->exists($this->s3_key);
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
            $this->storageDisk()->put($this->s3_key, fopen($file_path, 'r+'));
        } else {
            $this->storageDisk()->put($this->s3_key, fopen($file_path, 'r+'), $acl);
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
            $this->storageDisk()->put($this->s3_key, $file_contents);
        } else {
            $this->storageDisk()->put($this->s3_key, $file_contents, $acl);
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

        $mime = $this->storageDisk()->getMimetype($this->s3_key);
        $size = $this->storageDisk()->getSize($this->s3_key);

        $response = [
            'Content-Type' => $mime,
            'Content-Length' => $size,
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$file_name}",
            'Content-Transfer-Encoding' => 'binary',
        ];

        return Response::make($this->storageDisk()->get($this->s3_key), 200, $response);
    }

    /**
     * Delete a file or folder from an S3 bucket.
     *
     * @return bool
     */
    public function delete(): bool
    {
        return $this->storageDisk()->delete($this->s3_key);
    }

    /**
     * List all of the files in an S3 directory.
     *
     * @return array
     */
    public function list(): array
    {
        $storage = $this->storageDisk();
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
        [$base, $wildcard] = [dirname($this->s3_key), basename($this->s3_key)];

        // Get all of the folders in the base directory
        $folders = $this->storageDisk()->directories($base);

        // Filter folders to find the wildcard path
        $folders = array_filter($folders, function ($value) use ($base, $wildcard) {
            return str_starts_with($value, $base.DIRECTORY_SEPARATOR.$wildcard);
        });

        // return the resolved path
        $this->s3_key = collect($folders)->values()->first();

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
        $allFiles = $this->storageDisk()->allFiles($this->s3_key);

        if (isset($closure)) {
            return $closure($allFiles);
        }

        return $allFiles;
    }
}
