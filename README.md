# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kun391/users.svg?style=flat-square)](https://packagist.org/packages/kun391/users)
[![Build Status](https://img.shields.io/travis/kun391/users/master.svg?style=flat-square)](https://travis-ci.org/kun391/users)
[![Quality Score](https://img.shields.io/scrutinizer/g/kun391/users.svg?style=flat-square)](https://scrutinizer-ci.com/g/kun391/users)
[![Total Downloads](https://img.shields.io/packagist/dt/kun391/users.svg?style=flat-square)](https://packagist.org/packages/kun391/users)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require keeloren/file-management
```

## Usage

```
php artisan migrate
```
```
Put all variable to .env
# Media library config
STORAGE_DISK=local (minio, s3, gcs )
NAME_GENERATE=false
FOLDER_SAVE=library
PATH_TO_STORAGE=./data

# Minio config
MINIO_ACCESS_KEY=minio
MINIO_SECRET_KEY=minio123
MINIO_MINIO_ENDPOINT=http://minio:9000
MINIO_BUCKET=media

# AWS S3 config
AWS_ACCESS_KEY_ID=AKIARRW5OS3IQUSRVZPT
AWS_SECRET_ACCESS_KEY=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
AWS_DEFAULT_REGION=us-east-2
AWS_BUCKET=media-library-package
AWS_URL=https://media-library-package.s3.us-east-2.amazonaws.com/library

# Google Cloud config
GOOGLE_CLOUD_PROJECT_ID=trans-century-292502
GOOGLE_CLOUD_STORAGE_BUCKET=library-media
GOOGLE_CLOUD_KEY_FILE=

```
If you do not want sync data from minio, please don't set variable `PATH_TO_STORAGE`
### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email @gmail.com instead of using the issue tracker.

## Credits

- [Kee](https://github.com/keeloren)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
