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
