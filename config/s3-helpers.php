<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Temp File expiration
    |--------------------------------------------------------------------------
    |
    | Time for temp URLs generated for AWS S3 files to expire.
    |
    | Supported: "\DateTimeInterface"
    |
    */
    'expiration' => now()->addMinutes(60),

];
