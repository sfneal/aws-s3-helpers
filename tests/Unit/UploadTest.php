<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;
use Sfneal\Helpers\Aws\S3\Utils\CloudStorage;

class UploadTest extends StorageS3TestCase
{
    /**
     * @var string
     */
    protected $uploadPath;

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        Storage::delete($this->uploadPath);
        parent::tearDown();
    }

    /**
     * Execute upload test assertions.
     *
     * @param $file
     * @param $storage
     */
    protected function executeAssertions($file, $storage)
    {
        $s3Key = $storage->getKey();
        $exists = Storage::disk(config('filesystem.cloud', 's3'))->exists($s3Key);

        $this->assertInstanceOf(CloudStorage::class, $storage);
        $this->assertIsBool($exists);
        $this->assertTrue($exists, "The file '{$file}' doesn't exist.");
        $this->assertEquals(
            filesize(__DIR__.'/../Assets/'.$file),
            Storage::disk(config('filesystem.cloud', 's3'))->size($s3Key)
        );
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_can_be_uploaded(string $file)
    {
        $this->uploadPath = "uploaded_{$file}";
        $storage = StorageS3::key($this->uploadPath)
            ->disableStreaming()
            ->upload(__DIR__.'/../Assets/'.$file);

        $this->executeAssertions($file, $storage);
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_can_be_uploaded_streamed(string $file)
    {
        $this->uploadPath = "uploaded_{$file}";
        $storage = StorageS3::key($this->uploadPath)
            ->enableStreaming()
            ->upload(__DIR__.'/../Assets/'.$file);

        $this->executeAssertions($file, $storage);
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_can_be_uploaded_raw(string $file)
    {
        $this->uploadPath = "uploaded_{$file}";
        $storage = StorageS3::key($this->uploadPath)
            ->uploadRaw(file_get_contents(__DIR__.'/../Assets/'.$file));

        $this->executeAssertions($file, $storage);
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_can_be_uploaded_and_local_file_deleted(string $file)
    {
        $tempFile = "temp_{$file}";
        $this->assertTrue(copy(__DIR__.'/../Assets/'.$file, __DIR__.'/../Assets/'.$tempFile));

        $localPath = __DIR__.'/../Assets/'.$tempFile;
        $this->uploadPath = "uploaded_{$tempFile}";
        $this->assertTrue(file_exists($localPath));

        $storage = StorageS3::key($this->uploadPath)
            ->disableStreaming()
            ->deleteLocalFileAfterUpload()
            ->upload($localPath);

        $this->executeAssertions($file, $storage);
        $this->assertFalse(file_exists($localPath));
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_can_be_uploaded_and_local_file_deleted_with_streaming(string $file)
    {
        $tempFile = "temp_{$file}";
        $this->assertTrue(copy(__DIR__.'/../Assets/'.$file, __DIR__.'/../Assets/'.$tempFile));

        $localPath = __DIR__.'/../Assets/'.$tempFile;
        $this->uploadPath = "uploaded_{$tempFile}";
        $this->assertTrue(file_exists($localPath));

        $storage = StorageS3::key($this->uploadPath)
            ->enableStreaming()
            ->deleteLocalFileAfterUpload()
            ->upload($localPath);

        $this->executeAssertions($file, $storage);
        $this->assertFalse(file_exists($localPath));
    }
}
