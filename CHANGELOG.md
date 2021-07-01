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


## 0.5.0 - 2021-06-23
- start making `StorageS3` class that allows for static construction of an S3 object


## 0.5.1 - 2021-06-23
- fix issues with `Storage::disk()` method not being declared as static


## 0.6.0 - 2021-06-24
- add `autocompletePath()` method to `S3` for resolving partially complete directory paths with wildcard endings
- add `allFiles()` method to `S3` for retrieving all files in a directory and then applying a filter


## 0.6.1 - 2021-06-24
- cut default autoloading of 's3-helpers' helper function, now they must be manually autoloaded to avoid function name conflicts


## 0.6.2 - 2021-06-28
- refactor helper functions file to 'src/Helpers' directory


## 0.7.0 - 2021-06-28
- add league/flysystem-aws-s3-v3 & aws/aws-sdk-php composer dependency
- add `missing()` method to `S3Filesystem` interface & `S3` implementation
- cut support for php7.3 & below
- start creating test suite


## 0.7.1 - 2021-06-29
- cut `exists()`, `missing()` & `delete()` methods from `S3Filesystem` interface & `S3` implementation because they are functionally the same as `Storage` facade methods


## 0.7.2 - 2021-06-29
- fix issue with `s3_exists()` helper function breaking (fixed for use in blades to avoid imports)
- cut `s3_delete()` helper function as imports should be used instead


## 0.7.3 - 2021-06-29
- make `HelpersTest` for testing helper functions
- cut `s3_upload()`, `s3_download()` `s3_upload_raw()` & `s3_list()` helper functions


## 0.8.0 - 2021-06-29
- refactor helper functions to use camel case syntax & 's3{}' prefixes


## 0.8.1 - 2021-06-30
- fix issues with `autocompletePath()` method causing errors when resolving paths in the root directory
- add `getKey()` method to `S3`
- make `AllDirectoriesTest`, `AllFilesTest`, `AutocompletePathTest` & `DownloadTest`


## 0.9.0 - 2021-06-30
- fix return type of `S3` upload function to return the class instance instead of a url
- optimize `S3::upload()` & `S3::upload_raw()` methods
- refactor `S3::upload_raw()` to `S3::uploadRaw()`


## 0.10.0 - 2021-07-01
- optimize `s3FileUrl()` helper method by removing $temp param
- cut `S3::list()` method as it was behaving unexpectedly and is no longer used
- fix use of `Storage::disk('s3')` with `Storage::disk(config('filesystem.cloud', 's3'))` to make use of config


## 0.10.1 - 2021-07-01
- add file content assertions to test suites to more accurately determine if the tests were successful
- make `S3FilesystemTest`, `S3Test` & `StorageS3Test` tests for testing methods & interface implementations
