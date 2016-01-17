# Laravel CDN Assets Manager

[![Latest Stable Version](https://poser.pugx.org/publiux/laravelcdn/v/stable)](https://packagist.org/packages/publiux/laravelcdn)
[![Total Downloads](https://poser.pugx.org/publiux/laravelcdn/downloads)](https://packagist.org/packages/publiux/laravelcdn)
[![Build Status](https://travis-ci.org/publiux/laravelcdn.svg)](https://travis-ci.org/publiux/laravelcdn)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/publiux/laravelcdn/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/publiux/laravelcdn/?branch=master)
[![License](https://poser.pugx.org/publiux/laravelcdn/license)](https://packagist.org/packages/publiux/laravelcdn)


##### Content Delivery Network Package for Laravel

The package provides the developer the ability to upload his assets (or any public file) to a CDN with a single artisan command.
And then it allows him to switch between the local and the online version of the files.

#### Laravel Support
- For Laravel 5.2 and above use the latest realease (`master`).

## Highlights

- Amazon Web Services - S3
- Artisan command to upload content to CDN
- Simple Facade to access CDN assets


### Questions
1. Is this package an alternative to Laravel FileSystem and do they work together?

+ No, the package was introduced in Laravel 4 and it's main purpose is to manage your CDN assets by loading them from the CDN into your Views pages, and easily switch between your Local and CDN version of the files. As well it allows you to upload all your assets with single command after specifying the assets directory and rules. The FileSystem was introduced in Laravel 5 and it's designed to facilitate the loading/uploading of files form/to a CDN. It can be used the same way as this Package for loading assets from the CDN, but it's harder to upload your assets to the CDN since it expect you to upload your files one by one. As a result this package still not a replacement of the Laravel FileSystem and they can be used together.


## Installation

#### Via Composer

Require `publiux/laravelcdn` in your project:

```bash 
composer require publiux/laravelcdn
```

*Since this is a Laravel package we need to register the service provider:*

Add the service provider to `config/app.php`:

```php
'providers' => array(
     //...
     Publiux\laravelcdn\CdnServiceProvider::class,
),
```

## Configuration

Set the Credentials in the `.env` file.

*Note: you must have an `.env` file at the project root, to hold your sensitive information.*

```bash
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
```

Publish the package config file:

```bash
php artisan vendor:publish
```

You can find it at `config/cdn.php`


##### Default Provider
```php
'default' => 'AwsS3',
```

##### CDN Provider Configuration

```php
'aws' => [

    's3' => [
    
        'version'   => 'latest',
        'region'    => '',

        'buckets' => [
            'my-backup-bucket' => '*',
        ]
    ]
],
```

###### Multiple Buckets

```php
'buckets' => [

    'my-default-bucket' => '*',
    
    // 'js-bucket' => ['public/js'],
    // 'css-bucket' => ['public/css'],
    // ...
]

```

#### Files & Directories

###### Include:

Specify directories, extensions, files and patterns to be uploaded.

```php
'include'    => [
    'directories'   => ['public/dist'],
    'extensions'    => ['.js', '.css', '.yxz'],
    'patterns'      => ['**/*.coffee'],
],
```

###### Exclude:

Specify what to be ignored.

```php
'exclude'    => [
    'directories'   => ['public/uploads'],
    'files'         => [''],
    'extensions'    => ['.TODO', '.txt'],
    'patterns'      => ['src/*', '.idea/*'],
    'hidden'        => true, // ignore hidden files
],
```

##### URL

Set the CDN URL:

```php
'url' => 'https://s3.amazonaws.com',
```

##### Bypass

To load your LOCAL assets for testing or during development, set the `bypass` option to `true`:

```php
'bypass' => true,
```

##### Cloudfront Support

```php
'cloudfront'    => [
    'use' => false,
    'cdn_url' => ''
],
```


##### Other Configurations

```php
'acl'           => 'public-read',
'metadata'      => [ ],
'expires'       => gmdate("D, d M Y H:i:s T", strtotime("+5 years")),
'cache-control' => 'max-age=2628000',
```

You can always refer to the AWS S3 Documentation for more details: [aws-sdk-php](http://docs.aws.amazon.com/aws-sdk-php/v3/guide/)

## Usage

#### Push

Upload assets to CDN
```bash
php artisan cdn:push
```
#### Empty

Delete assets from CDN
```bash
php artisan cdn:empty
```

#### Load Assets

Use the facade `Cdn` to call the `Cdn::asset()` function.

*Note: the `asset` works the same as the Laravel `asset` it start looking for assets in the `public/` directory:*

```blade
{{Cdn::asset('assets/js/main.js')}}        // example result: https://js-bucket.s3.amazonaws.com/public/assets/js/main.js

{{Cdn::asset('assets/css/style.css')}}        // example result: https://css-bucket.s3.amazonaws.com/public/assets/css/style.css
```

To use a file from outside the `public/` directory, anywhere in `app/` use the `Cdn::path()` function:

```blade
{{Cdn::path('private/something/file.txt')}}        // example result: https://css-bucket.s3.amazonaws.com/private/something/file.txt
```


## Test

To run the tests, run the following command from the project folder.

```bash
$ ./vendor/bin/phpunit
```

## Support

[On Github](https://github.com/publiux/laravelcdn/issues)


## Contributing

Please see [CONTRIBUTING](https://github.com/publiux/laravelcdn/blob/master/CONTRIBUTING.md) for details.

## Security Related Issues

If you discover any security related issues, please email publiux@gmail.com instead of using the issue tracker for faster response. You should open an issue at the same time.

## Credits
- [Raul Ruiz](https://github.com/publiux) (forker)
- [Mahmoud Zalt](https://github.com/Mahmoudz) (original developer)
- [All Contributors](../../contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/publiux/laravelcdn/blob/master/LICENSE) for more information.
