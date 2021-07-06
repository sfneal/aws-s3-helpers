<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;

class UrlTest extends StorageS3TestCase
{
    /**
     * @throws GuzzleException
     */
    private function executeAssertions($url, $file)
    {
        $this->assertNotNull($url);
        $this->assertIsString($url);
        $this->assertStringContainsString($file, $url);

        $response = (new Client())->request('get', $url);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(file_get_contents($url), $response->getBody()->getContents());
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     * @throws GuzzleException
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
     * @throws GuzzleException
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
        try {
            $response = (new Client())->request('get', $url);
            $this->assertEquals(403, $response->getStatusCode());
        } catch (GuzzleException $exception) {
            $this->assertInstanceOf(GuzzleException::class, $exception);
            $this->assertEquals(403, $exception->getCode());
        }
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function temp_url_expired_is_invalid(string $file)
    {
        $url = StorageS3::key($file)->urlTemp(now()->subMinute());

        try {
            $response = (new Client())->request('get', $url);
            $this->assertEquals(400, $response->getStatusCode());
        } catch (GuzzleException $exception) {
            $this->assertInstanceOf(GuzzleException::class, $exception);
            $this->assertEquals(400, $exception->getCode());
        }
    }
}
