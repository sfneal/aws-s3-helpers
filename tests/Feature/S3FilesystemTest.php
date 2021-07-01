<?php


namespace Sfneal\Helpers\Aws\S3\Tests\Feature;


use Sfneal\Helpers\Aws\S3\Interfaces\S3Filesystem;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;

class S3FilesystemTest extends TestCase
{
    /** @test */
    public function interface_exist()
    {
        $this->assertTrue(interface_exists(S3Filesystem::class));
    }

    /** @test */
    public function interface_methods_exist()
    {
        $methods = [
            'url',
            'urlTemp',
            'upload',
            'uploadRaw',
            'download',
            'autocompletePath',
            'allFiles',
            'allDirectories',
        ];
        foreach ($methods as $method) {
            $this->assertTrue(method_exists(S3Filesystem::class, $method));
        }
    }
}
