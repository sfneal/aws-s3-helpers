<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Sfneal\Helpers\Aws\S3\StorageS3;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;

class AutocompletePathTest extends TestCase
{
    private function executeAssertions(string $expected, string $autocompleted)
    {
        $this->assertEquals($expected, $autocompleted);
        $this->assertTrue(Storage::disk('s3')->exists($autocompleted));
    }

    /** @test */
    public function nested_path_can_be_resolved()
    {
        $this->executeAssertions(
            'directory/2021/20210001_first',
            StorageS3::key('directory/2021/20210001')->autocompletePath()->getKey()
        );
    }

    /** @test */
    public function flat_path_can_be_resolved()
    {
        $this->executeAssertions(
            'directory_2021_06_30',
            StorageS3::key('directory_2021')->autocompletePath()->getKey()
        );
    }
}
