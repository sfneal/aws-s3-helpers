<?php


namespace Sfneal\Helpers\Aws\S3\Tests\Feature;


use Sfneal\Helpers\Aws\S3\Interfaces\S3Filesystem;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;
use Sfneal\Helpers\Aws\S3\Utils\S3;

class S3Test extends TestCase
{
    /** @test */
    public function implements_interface()
    {
        $this->assertContains(S3Filesystem::class, array_values(class_implements(S3::class)));
    }
}
