# LaraCDN

##### A CDN package for Laravel with S3 and Digitalocean Spaces Support
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
     Rehmatworks\laracdn\CdnServiceProvider::class,
),
```

```php
'aliases' => array(
     //...
     'Cdn' => Rehmatworks\laracdn\Facades\CdnFacadeAccessor::class
),
```

*If you are using Laravel 5.5, there is no need to register the service provider as this package is automatically discovered.*

Publish the package config file:

```bash
php artisan vendor:publish --provider 'Rehmatworks\laravelcdn\CdnServiceProvider'
```

## Environment Configuration
Configure these settings in your `.env` file

##### Required Configuration
```bash
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
CDN_Url=
```

Set to true if you want to enable these optional features
##### Optional Configuration
```bash
CDN_UseCloudFront=
CDN_CloudFrontUrl=
AWS_REGION=
AWS_Endpoint=
CDN_Bypass=
```

##### CDN Provider Configuration
Configure these settings in `config/cdn.php`

```php
'aws' => [
    's3' => [
        'version'   => 'latest',
        'region'    => env('AWS_REGION', 'nyc3'),
        'endpoint' => env('AWS_Endpoint', null),
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
    'endpoint' => env('AWS_Endpoint', null),
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
{{Cdn::asset('css/style.css')}} // example result: https://yourbucket.s3.amazonaws.com/public/css/style.css

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

#### v1.2
- Some fixes to namespaces