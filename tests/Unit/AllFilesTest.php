<?php


namespace Sfneal\Helpers\Aws\S3\Tests\Unit;


use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;

class AllFilesTest extends TestCase
{
    /** @test */
    public function all_files_can_be_found()
    {
        $expected = [
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
            'elevation.jpg',
            'floor-plan.png',
            'manual.pdf',
            'workbook.pdf',
        ];
        $allFiles = StorageS3::key('/')->allFiles();

        $this->assertEquals(sort($expected), sort($allFiles));
    }

    /** @test */
    public function all_files_in_directory_can_be_found()
    {
        $expected = [
            'article.pdf',
            'charts.pdf',
            'elevation.jpg',
            'floor-plan.png',
            'manual.pdf',
            'workbook.pdf',
            'elevation.jpg',
            'floor-plan.png',
            'manual.pdf',
            'workbook.pdf',
        ];
        $allFiles = StorageS3::key('directory/2021/20210002_second')->allFiles();

        $this->assertEquals(sort($expected), sort($allFiles));
    }
}
