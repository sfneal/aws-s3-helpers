<?php

namespace Sfneal\Helpers\Aws\S3\Utils\Interfaces;

use DateTimeInterface;

interface S3Accessors
{
    /**
     * Retrieve the S3 key (useful in conjunctions with `autocompletePath()` method).
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Return an S3 file url.
     *
     * @return string
     */
    public function url(): string;

    /**
     * Return a temporary S3 file url.
     *
     * @param DateTimeInterface|null $expiration
     * @return string
     */
    public function urlTemp(DateTimeInterface $expiration = null): string;
}
