<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Illuminate\Http\Response;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;

class DownloadTest extends StorageS3TestCase
{
    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function file_can_be_downloaded(string $file)
    {
        $storage = StorageS3::key($file);
        $response = $storage->download();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(file_get_contents($storage->url()), $response->content());
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function file_download_headers_are_correct(string $file)
    {
        $expectedHeaderKeys = [
            'Content-Type',
            'Content-Length',
            'Content-Description',
            'Content-Disposition',
            'Content-Transfer-Encoding',
        ];
        $storage = StorageS3::key($file);
        $response = $storage->download();

        foreach ($expectedHeaderKeys as $key) {
            $this->assertArrayHasKey(trim(strtolower($key)), $response->headers->all());
        }
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function file_download_response_code(string $file)
    {
        $storage = StorageS3::key($file);
        $response = $storage->download();

        $this->assertSame(200, $response->getStatusCode());
    }
}
