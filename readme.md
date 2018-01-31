# LaraCDN

##### A CDN package for Laravel that uses S3 and Digitalocean Spaces
This package allows you to upload your assets to a bucket at Amazon S3 or Digitalocean Spaces and use the assets from there in your views using a simple-to-use Facade.

###### Fork From [Publiux/laravelcdn](https://github.com/publiux/laravelcdn/)
This project has been forked from https://github.com/publiux/laravelcdn. All credit for the original work goes there.

#### Laravel Support
- This fork supports Laravel 5.2 up to an including Laravel 5.5 (`master`).
- Laravel 5.5 is supported, as is package auto-discovery.

## Highlights

- Amazon Web Services - S3
- Digitalocean Spaces
- Artisan command to upload content to CDN
- Simple Facade to access CDN assets

## Installation

#### Via Composer

Require `rehmatworks/laracdn` in your project:

```bash 
composer require "rehmatworks/laracdn"
```

* If you are using Laravel 5.4 or below, you need to register the service provider:*

Laravel 5.4 and below: Add the service provider and facade to `config/app.php`:

```php
'providers' => array(
     //...
     rehmatworks\laracdn\CdnServiceProvider::class,
),
```

```php
'aliases' => array(
     //...
     'Cdn' => rehmatworks\laracdn\Facades\CdnFacadeAccessor::class
),
```

*If you are using Laravel 5.5, there is no need to register the service provider as this package is automatically discovered.*

Publish the package config file:

```bash
php artisan vendor:publish --provider 'rehmatworks\laracdn\CdnServiceProvider'
```

## Environment Configuration

This package can be configured by editing the config/cdn.php file.  Alternatively, you can set many of these options in as environment variables in your '.env' file.

##### AWS Credentials
Set your AWS Credentials and other settings in the `.env` file.

* Note: you should always have an `.env` file at the project root, to hold your sensitive information. This file should usually not be committed to your VCS.*

```bash
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
```

##### CDN URL
Set the CDN URL:

```php
'url' => env('CDN_Url', 'https://s3.amazonaws.com'),
```

##### Bypass

To load your LOCAL assets for testing or during development, set the `bypass` option to `true`:

```php
'bypass' => env('CDN_Bypass', false),
```

This can be altered in your '.env' file as follows:

```bash
CDN_Bypass=
```

##### Cloudfront Support

```php
'cloudfront'    => [
    'use' => env('CDN_UseCloudFront', false),
    'cdn_url' => env('CDN_CloudFrontUrl', false)
],
```

This can be altered in your '.env' file as follows:

```bash
CDN_UseCloudFront=
CDN_CloudFrontUrl=
```

##### Default CDN Provider
For now, the only CDN provider available is AwsS3. This option cannot be set in '.env'.

```php
'default' => 'AwsS3',
```

##### CDN Provider Configuration
Configure these settings in `config/cdn.php`

```php
'aws' => [
    's3' => [
        'version'   => 'latest',
        'region'    => '',
        'endpoint' => null,
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




##### Other Configurations

```php
'acl'           => 'public-read',
'metadata'      => [ ],
'expires'       => gmdate("D, d M Y H:i:s T", strtotime("+5 years")),
'cache-control' => 'max-age=2628000',
```

You can always refer to the AWS S3 Documentation for more details: [aws-sdk-php](http://docs.aws.amazon.com/aws-sdk-php/v3/guide/)

## Usage

You can 'push' your assets to your CDN and you can 'empty' your assets as well using the commands below.

#### Push

Only changed assets are pushed to the CDN. (THanks, )

Upload assets to CDN
```bash
php artisan cdn:push
```

You can specify a folder upload prefix in the cdn.php config file. Your assets will be uploaded into that folder on S3.

#### Empty

Delete assets from CDN
```bash
php artisan cdn:empty
```
CAUTION: This will erase your entire bucket. This may not be what you want if you are specifying an upload folder when you push your assets.

#### Load Assets

Use the facade `Cdn` to call the `Cdn::asset()` function.

*Note: the `asset` works the same as the Laravel `asset` it start looking for assets in the `public/` directory:*

```blade
{{Cdn::asset('assets/js/main.js')}}        // example result: https://js-bucket.s3.amazonaws.com/public/assets/js/main.js

{{Cdn::asset('assets/css/style.css')}}        // example result: https://css-bucket.s3.amazonaws.com/public/assets/css/style.css
```
* Note: the `elixir` works the same as the Laravel `elixir` it loads the manifest.json file from build folder and choose the correct file revision generated by  gulp:*
```blade
{{Cdn::elixir('assets/js/main.js')}}        // example result: https://js-bucket.s3.amazonaws.com/public/build/assets/js/main-85cafe36ff.js

{{Cdn::elixir('assets/css/style.css')}}        // example result: https://css-bucket.s3.amazonaws.com/public/build/assets/css/style-2d558139f2.css
```
*Note: the `mix` works the same as the Laravel 5.4 `mix` it loads the mix-manifest.json file from public folder and choose the correct file revision generated by webpack:*
```blade
{{Cdn::mix('/js/main.js')}}        // example result: https://js-bucket.s3.amazonaws.com/public/js/main-85cafe36ff.js

{{Cdn::mix('/css/style.css')}}        // example result: https://css-bucket.s3.amazonaws.com/public/css/style-2d558139f2.css
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

Please request support or submit issues [via Github](https://github.com/rehmatworks/laracdn/issues)


## Contributing

Please see [CONTRIBUTING](https://github.com/rehmatworks/laracdn/blob/master/CONTRIBUTING.md) for details.

## Security Related Issues

If you discover any security related issues, please email contact@rehmat.works instead of using the issue tracker for faster response. You should open an issue at the same time.

## Credits
- [Raul Ruiz](https://github.com/rehmatworks) (forker)
- [Mahmoud Zalt](https://github.com/Mahmoudz) (original developer)
- [Filipe Garcia](https://github.com/filipegar) (contributred pre-fork, uncredited pull request for duplicate uploading verification)
- [Contributors from original project](https://github.com/Vinelab/cdn/graphs/contributors)
- [All Contributors for this Fork](../../contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/Rehmatworks/laravelcdn/blob/master/LICENSE) for more information.

## Changelog

#### v1.0
- Initial release
