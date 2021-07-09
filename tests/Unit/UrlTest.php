<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Illuminate\Support\Facades\Http;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;

class UrlTest extends StorageS3TestCase
{
    private function executeAssertions($url, $file)
    {
        $this->assertNotNull($url);
        $this->assertIsString($url);
        $this->assertStringContainsString($file, $url);

        $response = Http::get($url);

        $this->assertTrue($response->ok());
        $this->assertEquals(file_get_contents($url), $response->body());
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function url_is_valid(string $file)
    {
        $url = StorageS3::key($file)->url();

        $this->executeAssertions($url, $file);
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function temp_url_is_valid(string $file)
    {
        $url = StorageS3::key($file)->urlTemp();

        $this->executeAssertions($url, $file);
        $this->assertNotEquals(StorageS3::key($file)->url(), $url);
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function temp_url_with_after_expiration_is_invalid(string $file)
    {
        $url = StorageS3::key($file)->urlTemp(now()->addSecond());

        sleep(1);
        $response = Http::get($url);
        $this->assertEquals(403, $response->status());
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function temp_url_expired_is_invalid(string $file)
    {
        $url = StorageS3::key($file)->urlTemp(now()->subMinute());

        $response = Http::get($url);
        $this->assertEquals(400, $response->status());
    }
}
