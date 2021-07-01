<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;

class UploadTest extends StorageS3TestCase
{
    /**
     * @var string
     */
    private $uploadPath;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->uploadPath = "uploaded_{$this->file}";
        parent::setUp();
    }

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

    /** @test */
    public function file_can_be_uploaded()
    {
        $s3Key = StorageS3::key($this->uploadPath)
            ->upload(__DIR__.'/../Assets/'.$this->file)
            ->getKey();

        $exists = Storage::disk(config('filesystem.cloud', 's3'))->exists($s3Key);

        $this->assertIsBool($exists);
        $this->assertTrue($exists, "The file '{$this->file}' doesn't exist.");
        $this->assertEquals(
            Storage::size($this->uploadPath),
            Storage::disk(config('filesystem.cloud', 's3'))->size($s3Key)
        );
    }

    /** @test */
    public function file_can_be_uploaded_raw()
    {
        $s3Key = StorageS3::key($this->uploadPath)
            ->uploadRaw(file_get_contents(__DIR__.'/../Assets/'.$this->file))
            ->getKey();

        $exists = Storage::disk(config('filesystem.cloud', 's3'))->exists($s3Key);

        $this->assertIsBool($exists);
        $this->assertTrue($exists, "The file '{$this->file}' doesn't exist.");
        $this->assertEquals(
            Storage::size($this->uploadPath),
            Storage::disk(config('filesystem.cloud', 's3'))->size($s3Key)
        );
    }
}
