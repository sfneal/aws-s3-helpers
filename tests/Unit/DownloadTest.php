<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Illuminate\Http\Response;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;

class DownloadTest extends StorageS3TestCase
{
    /** @test */
    public function file_can_be_downloaded()
    {
        $storage = StorageS3::key($this->file);
        $response = $storage->download();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(file_get_contents($storage->url()), $response->content());
    }

    /** @test */
    public function file_download_headers_are_correct()
    {
        $expectedHeaderKeys = [
            'Content-Type',
            'Content-Length',
            'Content-Description',
            'Content-Disposition',
            'Content-Transfer-Encoding',
        ];
        $storage = StorageS3::key($this->file);
        $response = $storage->download();

        foreach ($expectedHeaderKeys as $key) {
            $this->assertArrayHasKey(trim(strtolower($key)), $response->headers->all());
        }
    }

    /** @test */
    public function file_download_response_code()
    {
        $storage = StorageS3::key($this->file);
        $response = $storage->download();

        $this->assertSame(200, $response->getStatusCode());
    }
}
