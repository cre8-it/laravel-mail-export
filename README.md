# Laravel Mail Export

[![Latest Version on Packagist](https://img.shields.io/packagist/v/pod-point/laravel-mail-export.svg?style=flat-square)](https://packagist.org/packages/pod-point/laravel-mail-export)
[![tests](https://github.com/Pod-Point/laravel-mail-export/actions/workflows/run-tests.yml/badge.svg?branch=2.x)](https://github.com/Pod-Point/laravel-mail-export/actions/workflows/run-tests.yml)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

This is a Fork from [Pod-Point](https://github.com/Pod-Point/laravel-mail-export) to provide Support for laravel 12 and Carbon 3

This package can export any mail sent with Laravel's `Mailable` class to any desired filesystem disk and path as a `.eml` file.

This can be useful when wanting to store emails sent for archive purposes.

## Installation

For Laravel 9.x, 10.x, 11.x, 12.x (requires PHP version 8.2 or higher)
Add the Fork to as a VCS repository to your `composer.json`
```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/cre8-it/laravel-mail-export"
        }
    ]
}
```

After this you can require the package using composer
```bash
composer require pod-point/laravel-mail-export:@dev
```
#
Or update you dependency constraint to reference the target branch with a `dev-` prefix
```json
{
    "require": {
        "pod-point/laravel-mail-export": "dev-main"
    }
}
```
And update the package using composer
```bash
composer update pod-point/laravel-mail-export
```

### Publishing the config file

The configuration for this package comes with some sensible values but you can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="PodPoint\MailExport\MailExportServiceProvider"
```

You will be able to specify:

* `enabled`: whether this package is enabled or not. Once installed, it's enabled by default but the `MAIL_EXPORT` environment variable can be used to configure this.
* `disk`: which disk to use by default. `null` will use the default disk from your application filesystem.
* `path`: the default path, within the configured disk, where mail will be exported.

See our [`config/mail-export.php`](config/mail-export.php) for more details.

## Usage

Simply add the `Exportable` trait and the `ShouldExport` interface to any Mailable class that you want to persist into any storage disk.

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class OrderShipped extends Mailable implements ShouldExport
{
    use Exportable;

    // ...
}
```

This will use the default filesystem `disk` and `path` from the configuration and will also generate a unique `filename` for you.

The default filename is using a timestamp, the mail recipients, the subject and will look like so:

```
2021_03_26_150142_jane_at_example_com_this_is_the_subject.eml
```

You can also specify the `disk`, `path` or `filename` to use for a specific Mailable using properties:

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class OrderShipped extends Mailable implements ShouldExport
{
    use Exportable;

    public $exportDisk = 'some_disk';

    public $exportPath = 'some_path';

    public $exportFilename = 'some_filename';

    // ...
}
```

You can also use methods if you need more flexibility:

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use PodPoint\MailExport\Concerns\Exportable;
use PodPoint\MailExport\Contracts\ShouldExport;

class OrderShipped extends Mailable implements ShouldExport
{
    use Exportable;

    // ...

    public function exportDisk(): string
    {
        return 'some_disk';
    }

    public function exportPath(): string
    {
        return 'some_path';
    }

    public function exportFilename(): string
    {
        return 'some_filename';
    }
}
```

Then you can keep using your `Mailable` as usual:

```php
Mail::to($request->user())->send(new OrderShipped($order));
```

Even with Notifications too:

```php
<?php

namespace App\Notifications;

use App\Mail\OrderShipped as Mailable;
use Illuminate\Notifications\Notification;

class OrderShipped extends Notification
{
    // ...

    public function toMail($notifiable)
    {
        return (new Mailable($this->order))->to($notifiable->email);
    }
}
```

## Testing

Run the tests with:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [themsaid](https://github.com/themsaid) and Spatie's [laravel-mail-preview](https://github.com/spatie/laravel-mail-preview) for some inspiration
- [Laravel Package Development](https://laravelpackage.com) documentation by [John Braun](https://github.com/Jhnbrn90)
- [Pod Point](https://github.com/pod-point)
- [All Contributors](https://github.com/pod-point/laravel-mail-export/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENCE.md) for more information.

---

<img src="https://d3h256n3bzippp.cloudfront.net/pod-point-logo.svg" align="right" />

Travel shouldn't damage the earth 🌍

Made with ❤️&nbsp;&nbsp;at [Pod Point](https://pod-point.com)
