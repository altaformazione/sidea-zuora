
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# This is my zuora api client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sideagroup/zuora.svg?style=flat-square)](https://packagist.org/packages/sideagroup/zuora)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sideagroup/zuora/run-tests?label=tests)](https://github.com/sideagroup/zuora/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/sideagroup/zuora/Check%20&%20fix%20styling?label=code%20style)](https://github.com/sideagroup/zuora/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sideagroup/zuora.svg?style=flat-square)](https://packagist.org/packages/sideagroup/zuora)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:
1. Add in composer.json
   ```json
    "repositories": [
        {
            "type": "composer",
            "url": "https://php-pkg.sideagroup.com/"
        }
    ]
   ```
2. Run
    ```bash
    composer require sideagroup/zuora
    ```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="zuora-config"
```

This is the contents of the published config file:

```php
<?php

// config for Sideagroup/Zuora
return [
    'base_uri' => env('ZUORA_BASE_URI', 'https://rest.eu.zuora.com'),
    'credentials' => [
        'client_id' => env('ZUORA_CLIENT_ID'),
        'client_secret' => env('ZUORA_CLIENT_SECRET'),
    ]
];
```
## Usage

```php
\Sideagroup\Zuora\V2\Facades\ZuoraApiClient::request(...)
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Francesco Liuzzi](https://github.com/franc-liuzzi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
