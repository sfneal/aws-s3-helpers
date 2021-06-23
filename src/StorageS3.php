<?php

namespace Sfneal\Helpers\Aws\S3;

class StorageS3
{
    /**
     * Instantiate an S3 object while only providing an S3 object key.
     *
     * @param string $key
     * @return S3
     */
    public static function key(string $key): S3
    {
        return new S3($key);
    }

    /**
     * * Instantiate an S3 object while providing a filesystem disk and an S3 object key.
     *
     * @param string $disk
     * @param string $key
     * @return S3
     */
    public function disk(string $disk, string $key): S3
    {
        return self::key($key)->setDisk($disk);
    }
}
