<?php

namespace Sfneal\Helpers\Aws\S3\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;

    /**
     * Define environment setup.
     *
     * @param Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.disks.s3', [
            'driver' => 's3',
            'key' => env('S3_KEY'),
            'secret' => env('S3_SECRET'),
            'region' => env('S3_REGION'),
            'bucket' => env('S3_BUCKET'),
            'root' => env('S3_ROOT'),
        ]);

        $app['config']->set('filesystems.default', 's3');
    }
}
