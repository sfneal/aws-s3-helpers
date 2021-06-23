# Changelog

All notable changes to `aws-s3-helpers` will be documented in this file

## 0.1.0 - 2020-09-08
- initial release


## 0.1.1 - 2020-09-08
- add use of S3 class in helper functions


## 0.2.0 - 2020-09-08
- fix composer.json to allow for laravel/framework:^8


## 0.3.0 - 2020-10-08
- add support for php7.0-7.1


## 0.4.0 - 2020-12-11
- add support for php8


## 0.4.1 - 2021-06-23
- optimize Travis CI config & add code coverage upload support
- optimize `S3` methods return type hinting
- add $expiration param to `url()` method for specifying a temp url TTL
- add `S3::urlTemp()` method for retrieving temp urls
