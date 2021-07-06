<?php


namespace Sfneal\Helpers\Aws\S3\Tests;


use Illuminate\Support\Facades\Storage;

abstract class UploadTestCase extends StorageS3TestCase
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
     * @param $s3Key
     */
    protected function executeAssertions($file, $s3Key)
    {
        $exists = Storage::disk(config('filesystem.cloud', 's3'))->exists($s3Key);

        $this->assertIsBool($exists);
        $this->assertTrue($exists, "The file '{$file}' doesn't exist.");
        $this->assertEquals(
            Storage::size($this->uploadPath),
            Storage::disk(config('filesystem.cloud', 's3'))->size($s3Key)
        );
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    abstract public function file_can_be_uploaded(string $file);

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    abstract public function file_can_be_uploaded_raw(string $file);
}
