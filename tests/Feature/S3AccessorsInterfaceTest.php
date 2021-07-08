<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Feature;

use Sfneal\Helpers\Aws\S3\Interfaces\S3Accessors;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;
use Sfneal\Helpers\Aws\S3\Utils\CloudStorage;
use Sfneal\Testing\Utils\Traits\InterfaceTest;

class S3AccessorsInterfaceTest extends TestCase
{
    use InterfaceTest;

    /**
     * Retrieve the interface class that should be tested.
     *
     * @return string
     */
    public function interface(): string
    {
        return S3Accessors::class;
    }

    /**
     * Retrieve an array of classes that should implement the interface.
     *
     * @return array
     */
    public function classes(): array
    {
        return [
            CloudStorage::class
        ];
    }

    /**
     * Retrieve an array of method names that should exist in the interface.
     *
     * @return array
     */
    public function methods(): array
    {
        return [
            'getKey',
            'setDisk',
            'url',
            'urlTemp',
        ];
    }
}
