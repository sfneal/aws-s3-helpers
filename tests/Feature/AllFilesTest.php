<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;

class AllFilesTest extends TestCase
{
    private function executeAssertions($expected, $allFiles)
    {
        $this->assertNotNull($allFiles);
        $this->assertIsArray($allFiles);

        foreach($expected as $expect) {
            $this->assertTrue(in_array($expect, $allFiles));
            Storage::disk(config('filesystem.cloud', 's3'))->exists($expect);
        }

        sort($expected);
        sort($allFiles);
        $this->assertEquals($expected, $allFiles);
    }

    /** @test */
    public function all_files_can_be_found()
    {
        $this->executeAssertions(
            [
                'article.pdf',
                'charts.pdf',
                'elevation.jpg',
                'floor-plan.png',
                'manual.pdf',
                'workbook.pdf',
                'directory/2021/20210002_second/article.pdf',
                'directory/2021/20210002_second/charts.pdf',
                'directory/2021/20210002_second/elevation.jpg',
                'directory/2021/20210002_second/floor-plan.png',
                'directory/2021/20210002_second/manual.pdf',
                'directory/2021/20210002_second/workbook.pdf',
            ],
            StorageS3::key('/')->allFiles()
        );
    }

    /** @test */
    public function all_files_in_directory_can_be_found()
    {
        $this->executeAssertions(
            [
                'directory/2021/20210002_second/article.pdf',
                'directory/2021/20210002_second/charts.pdf',
                'directory/2021/20210002_second/elevation.jpg',
                'directory/2021/20210002_second/floor-plan.png',
                'directory/2021/20210002_second/manual.pdf',
                'directory/2021/20210002_second/workbook.pdf',
            ],
            StorageS3::key('directory/2021/20210002_second')->allFiles()
        );
    }
}
