<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use GuzzleHttp\Client;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\StorageS3TestCase;

class HelpersTest extends StorageS3TestCase
{
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
     */
    public function file_url_exists(string $file)
    {
        $url = s3FileURL($file);

        $this->executeAssertions($url, $file);
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_url_temp_exists(string $file)
    {
        $url = s3FileUrlTemp($file);

        $this->executeAssertions($url, $file);
        $this->assertNotEquals(StorageS3::key($file)->url(), $url);
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_exists(string $file)
    {
        $exists = s3Exists($file);

        $this->assertIsBool($exists);
        $this->assertTrue($exists, "The file '{$file}' doesn't exist.");
    }

    /**
     * @test
     * @dataProvider fileProvider
     * @param string $file
     */
    public function file_doesnt_exist(string $file)
    {
        $doesntExist = s3Exists("fake_file_{$file}");

        $this->assertIsBool($doesntExist);
        $this->assertFalse($doesntExist);
    }

    /** @test */
    public function directory_exists()
    {
        $exists = s3Exists('directory');

        $this->assertIsBool($exists);
        $this->assertTrue($exists);
    }

    /** @test */
    public function directory_doesnt_exist()
    {
        $doesntExist = s3Exists('fake_directory');

        $this->assertIsBool($doesntExist);
        $this->assertFalse($doesntExist);
    }
}
