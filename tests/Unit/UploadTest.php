<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\UploadTestCase;

class UploadTest extends UploadTestCase
{
    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_can_be_uploaded(string $file)
    {
        $this->uploadPath = "uploaded_{$file}";
        $s3Key = StorageS3::key($this->uploadPath)
            ->upload(__DIR__.'/../Assets/'.$file)
            ->getKey();

        $this->executeAssertions($file, $s3Key);
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_can_be_uploaded_raw(string $file)
    {
        $this->uploadPath = "uploaded_{$file}";
        $s3Key = StorageS3::key($this->uploadPath)
            ->uploadRaw(file_get_contents(__DIR__.'/../Assets/'.$file))
            ->getKey();

        $this->executeAssertions($file, $s3Key);
    }
}
