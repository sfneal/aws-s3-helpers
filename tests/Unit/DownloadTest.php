<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
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
        $fileName = $fileName ?? basename($file);
        $storage = Storage::disk(config('filesystem.cloud', 's3'));
        $expectedHeaderKeys = [
            'Content-Type' => $storage->getMimetype($file),
            'Content-Length' => $storage->getSize($file),
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$fileName}",
            'Content-Transfer-Encoding' => 'binary',
        ];
        $storage = StorageS3::key($file);
        $response = $storage->download();

        foreach ($expectedHeaderKeys as $key => $value) {
            $this->assertArrayHasKey(trim(strtolower($key)), $response->headers->all());
            $this->assertEquals($value, $response->headers->get($key));
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
