<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;

class AllFilesTest extends TestCase
{
    private function executeAssertions($expected, $allFiles)
    {
        $array = $allFiles->toArray();

        $this->assertNotNull($allFiles);
        $this->assertInstanceOf(Collection::class, $allFiles);
        $this->assertIsArray($array);

        foreach ($expected as $expect) {
            $this->assertTrue(in_array($expect, $array));
            Storage::disk(config('filesystem.cloud', 's3'))->exists($expect);
        }

        sort($expected);
        sort($array);
        $this->assertEquals($expected, $array);
    }

    /** @test */
    public function all_files_can_be_found()
    {
        $this->executeAssertions(
            [
                'article.pdf',
                'con-docs.pdf',
                'charts.pdf',
                'elevation.jpg',
                'floor-plan.png',
                'manual.pdf',
                'workbook.pdf',
                'directory/2021/20210002_second/article.pdf',
                'directory/2021/20210002_second/con-docs.pdf',
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
                'directory/2021/20210002_second/con-docs.pdf',
                'directory/2021/20210002_second/elevation.jpg',
                'directory/2021/20210002_second/floor-plan.png',
                'directory/2021/20210002_second/manual.pdf',
                'directory/2021/20210002_second/workbook.pdf',
            ],
            StorageS3::key('directory/2021/20210002_second')->allFiles()
        );
    }
}
