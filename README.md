# AWS S3 Helpers

[![Packagist PHP support](https://img.shields.io/packagist/php-v/sfneal/aws-s3-helpers)](https://packagist.org/packages/sfneal/aws-s3-helpers)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/sfneal/aws-s3-helpers.svg?style=flat-square)](https://packagist.org/packages/sfneal/aws-s3-helpers)
[![Build Status](https://travis-ci.com/sfneal/aws-s3-helpers.svg?branch=master&style=flat-square)](https://travis-ci.com/sfneal/aws-s3-helpers)
[![Quality Score](https://img.shields.io/scrutinizer/g/sfneal/aws-s3-helpers.svg?style=flat-square)](https://scrutinizer-ci.com/g/sfneal/aws-s3-helpers)
[![Total Downloads](https://img.shields.io/packagist/dt/sfneal/aws-s3-helpers.svg?style=flat-square)](https://packagist.org/packages/sfneal/aws-s3-helpers)

Abstraction layers for interacting with AWS S3 storage.

## Installation

You can install the package via composer:

```bash
composer require sfneal/aws-s3-helpers
```

In order to autoload to the helper functions add the following path to the autoload.files section in your composer.json.

```json
"autoload": {
    "files": [
      "vendor/sfneal/aws-s3-helpers/src/Helpers/s3-helpers.php"
    ]
},
```

To modify the s3-helpers settings publish the ServiceProvider & modify the config.

``` php
php artisan vendor:publish --provider="Sfneal\Helpers\Aws\S3\Providers\S3HelpersServiceProvider"
```

## Usage
Add 's3' disk to the 'disks' array in config/filesystems.php with your own AWS credentials.

``` php
's3' => [
    'driver' => 's3',
    'key' => env('S3_KEY'),
    'secret' => env('S3_SECRET'),
    'region' => env('S3_REGION'),
    'bucket' => env('S3_BUCKET'),
],
```

Add S3 keys to your .env files.

```php
S3_KEY=********************
S3_SECRET=****************************************
S3_REGION=*********
S3_BUCKET=******************
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email stephen.neal14@gmail.com instead of using the issue tracker.

## Credits

- [Stephen Neal](https://github.com/sfneal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
