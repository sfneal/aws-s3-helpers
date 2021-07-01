<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Feature;

use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;

class AllDirectoriesTest extends TestCase
{
    private function executeAssertions($expected, $allDirectories)
    {
        $this->assertNotNull($allDirectories);
        $this->assertIsArray($allDirectories);

        foreach($expected as $expect) {
            $this->assertTrue(in_array($expect, $allDirectories));
        }

        sort($expected);
        sort($allDirectories);
        $this->assertEquals($expected, $allDirectories);
    }

    /** @test */
    public function all_directories_can_be_found()
    {
        $this->executeAssertions(
            [
                'directory',
                'directory/2021',
                'directory/2021/20210001_first',
                'directory/2021/20210002_second',
                'directory_2021_06_30',
            ],
            StorageS3::key('/')->allDirectories()
        );
    }

    /** @test */
    public function all_directories_in_directory_can_be_found()
    {
        $this->executeAssertions(
            [
                'directory/2021/20210001_first',
                'directory/2021/20210002_second',
            ],
            StorageS3::key('directory/2021')->allDirectories()
        );
    }
}
