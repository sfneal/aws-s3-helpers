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
    private function executeAssertions($url)
    {
        $this->assertNotNull($url);
        $this->assertIsString($url);
        $this->assertStringContainsString($this->file, $url);

        $response = (new Client())->request('get', $url);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(file_get_contents($url), $response->getBody()->getContents());
    }

    /** @test */
    public function url_is_valid()
    {
        $url = StorageS3::key($this->file)->url();

        $this->executeAssertions($url);
    }

    /** @test */
    public function temp_url_is_valid()
    {
        $url = StorageS3::key($this->file)->urlTemp();

        $this->executeAssertions($url);
        $this->assertNotEquals(StorageS3::key($this->file)->url(), $url);
    }
}
