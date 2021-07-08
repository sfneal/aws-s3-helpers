<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Feature;

use Sfneal\Helpers\Aws\S3\Tests\TestCase;
use Sfneal\Helpers\Aws\S3\Utils\Interfaces\S3Actions;
use Sfneal\Helpers\Aws\S3\Utils\S3;

class S3Test extends TestCase
{
    // todo: improve this

    /** @test */
    public function implements_interface()
    {
        $this->assertContains(S3Actions::class, array_values(class_implements(S3::class)));
    }
}
