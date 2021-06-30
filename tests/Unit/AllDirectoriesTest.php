<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;

class AllDirectoriesTest extends TestCase
{
    /** @test */
    public function all_directories_can_be_found()
    {
        $expected = [
            'directory',
            'directory/2021',
            'directory/2021/20210001_first',
            'directory/2021/20210002_second',
            'directory_2021_06_30',
        ];
        $allDirectories = StorageS3::key('/')->allDirectories();

        sort($expected);
        sort($allDirectories);
        $this->assertEquals($expected, $allDirectories);
    }

    /** @test */
    public function all_directories_in_directory_can_be_found()
    {
        $expected = [
            'directory/2021/20210001_first',
            'directory/2021/20210002_second',
        ];
        $allDirectories = StorageS3::key('directory/2021')->allDirectories();

        sort($expected);
        sort($allDirectories);
        $this->assertEquals($expected, $allDirectories);
    }
}
