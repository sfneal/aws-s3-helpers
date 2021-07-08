<?php

namespace Sfneal\Helpers\Aws\S3\Tests\Feature;

use Sfneal\Helpers\Aws\S3\Interfaces\S3Actions;
use Sfneal\Helpers\Aws\S3\Tests\TestCase;
use Sfneal\Helpers\Aws\S3\Utils\S3;
use Sfneal\Testing\Utils\Traits\InterfaceTest;

class S3ActionsInterfaceTest extends TestCase
{
    use InterfaceTest;

    /**
     * Retrieve the interface class that should be tested.
     *
     * @return string
     */
    public function interface(): string
    {
        return S3Actions::class;
    }

    /**
     * Retrieve an array of classes that should implement the interface.
     *
     * @return array
     */
    public function classes(): array
    {
        return [
            S3::class
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
            'upload',
            'uploadRaw',
            'download',
            'autocompletePath',
            'allFiles',
            'allDirectories',
        ];
    }
}
