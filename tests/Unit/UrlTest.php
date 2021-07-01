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
}
