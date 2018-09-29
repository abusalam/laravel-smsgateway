# Send SMS from your Laravel app [![License](https://poser.pugx.org/abusalam/laravel-smsgateway/license)](https://packagist.org/packages/abusalam/laravel-smsgateway) 

[![Latest Stable Version](https://poser.pugx.org/abusalam/laravel-smsgateway/v/stable)](https://packagist.org/packages/abusalam/laravel-smsgateway) [![Total Downloads](https://poser.pugx.org/abusalam/laravel-smsgateway/downloads)](https://packagist.org/packages/abusalam/laravel-smsgateway) [![Build Status](https://scrutinizer-ci.com/g/abusalam/laravel-smsgateway/badges/build.png?b=dev)](https://scrutinizer-ci.com/g/abusalam/laravel-smsgateway/build-status/dev) [![Code Intelligence Status](https://scrutinizer-ci.com/g/abusalam/laravel-smsgateway/badges/code-intelligence.svg?b=dev)](https://scrutinizer-ci.com/code-intelligence) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/abusalam/laravel-smsgateway/badges/quality-score.png?b=dev)](https://scrutinizer-ci.com/g/abusalam/laravel-smsgateway/?branch=dev)

The `abusalam/laravel-smsgateway` package provides easy to use functions to send sms from your app. Works with CDAC SMS Gateway Services out of the box.

Here's a demo of how you can use it:

```php
use AbuSalam\SmsGateway;

# Code...

$smsGateway = new SmsGateway;
$smsGateway->toRecipient('9876543210')
    ->withSms('Computer science is no more about computers than astronomy is about telescopes. - Edsger Dijkstra')
    ->sendSms();

# Code...

```

## Documentation
You'll find the documentation here.


## Installation

You can install the package via composer:

``` bash
composer require "abusalam/laravel-smsgateway"
```

The package will automatically register itself.

You can optionally publish the config file with:
```bash
php artisan vendor:publish --provider="AbuSalam\SmsGatewayServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Default SMS Gateway Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the SMS Gateways below you wish
    | to use as your default SMS Gateway for sending SMSs. Of course
    | you may use many connections at once using the SMS Gateway library.
    |
    */
    'default' => env('SMS_GATEWAY', 'local'),

    /*
    |--------------------------------------------------------------------------
    | SMS Gateways
    |--------------------------------------------------------------------------
    |
    | Here are each of the SMS Gateways setup for your application.
    | Of course, examples of configuring each SMS Gateway platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All SMS Gateway work in Laravel is done through the PHP SMS facilities
    | so make sure you have the driver for your particular SMS Gateway of
    | choice installed on your machine before you begin development.
    |
    */

    /*=============================================================
    =            Default SMS Gateway API Configuration            =
    =============================================================*/
    'cdacSms' => [
        /* SMS Gateway API Endpoint Configurations */
        'apiEndpoint'       => env('SMS_URL', 'https://msdgweb.mgov.gov.in/esms/sendsmsrequest'),
        'apiMobileNoParam'  => env('SMS_MOBILE_NO_PARAM', 'mobileno'),
        'apiSmsParam'       => env('SMS_SMS_PARAM', 'content'),
        /* SMS Gateway Parameter Configurations */
        'apiParams'         => [
            'apiUserParam'      => env('SMS_USERNAME_PARAM', 'username'),
            'apiPassParam'      => env('SMS_PASSWORD_PARAM', 'password'),
            'apiSenderIdParam'  => env('SMS_SENDER_ID_PARAM', 'senderid'),
            'apiSecureKeyParam' => env('SMS_API_KEY_PARAM', 'key'),
            'apiServiceTypeParam' => env('SMS_SERVICE_TYPE_PARAM', 'smsservicetype'),
        ],
        'apiValues'     => [
            'apiUser'       => env('SMS_USERNAME', 'dummyuser'),
            'apiPass'       => sha1(trim(env('SMS_PASSWORD', 'dummypass'))),
            'apiSenderId'   => env('SMS_SENDER_ID', 'DUMMY'),
            'apiSecureKey'  => env('SMS_API_KEY', 'top-secret-dummy-key'),
            'apiServiceType' => env('SMS_SERVICE_TYPE_PARAM', 'singlemsg'),
        ]
    ],
    /*=====  End of Default SMS Gateway API Configuration  ======*/

    'local' => [
        /* SMS Gateway API Endpoint Configurations */
        'apiEndpoint'       => 'http://insp.local.host/api/sms',
        'apiMobileNoParam'  => 'recipient',
        'apiSmsParam'       => 'sms',
        /* SMS Gateway Parameter Configurations */
        'apiParams'         => [
            'apiUserParam'      => env('SMS_USERNAME_PARAM', 'username'),
            'apiPassParam'      => env('SMS_PASSWORD_PARAM', 'password'),
            'apiSenderIdParam'  => env('SMS_SENDER_ID_PARAM', 'senderid'),
            'apiSecureKeyParam' => env('SMS_API_KEY_PARAM', 'key'),
        ],
        'apiValues'     => [
            'apiUser'       => env('SMS_USERNAME', 'dummyuser'),
            'apiPass'       => sha1(trim(env('SMS_PASSWORD', 'dummypass'))),
            'apiSenderId'   => env('SMS_SENDER_ID', 'DUMMY'),
            'apiSecureKey'  => env('SMS_API_KEY', 'top-secret-dummy-key'),
        ]
    ],
];

```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
