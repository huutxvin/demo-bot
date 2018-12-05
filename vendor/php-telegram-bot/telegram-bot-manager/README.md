# PHP Telegram Bot Manager

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/noplanman/php-telegram-bot-manager.svg?maxAge=2592000)](https://scrutinizer-ci.com/g/noplanman/php-telegram-bot-manager/?branch=master)
[![Codecov](https://img.shields.io/codecov/c/github/noplanman/php-telegram-bot-manager.svg?maxAge=2592000)](https://codecov.io/gh/noplanman/php-telegram-bot-manager)
[![Build Status](https://img.shields.io/travis/noplanman/php-telegram-bot-manager.svg?maxAge=2592000)](https://travis-ci.org/noplanman/php-telegram-bot-manager)

[![Latest Stable Version](https://img.shields.io/packagist/v/noplanman/telegram-bot-manager.svg?maxAge=2592000)](https://packagist.org/packages/noplanman/telegram-bot-manager)
[![Total Downloads](https://img.shields.io/packagist/dt/noplanman/telegram-bot-manager.svg?maxAge=2592000)](https://packagist.org/packages/noplanman/telegram-bot-manager)
[![License](https://img.shields.io/packagist/l/noplanman/telegram-bot-manager.svg?maxAge=2592000)](https://github.com/noplanman/php-telegram-bot-manager/LICENSE.md)

This project builds on top of [PHP Telegram Bot](https://github.com/akalongman/php-telegram-bot/) and as such, depends on it!

The main purpose of this mini-library is to make the interaction between your webserver and Telegram easier.
I strongly suggest your read the PHP Telegram Bot [instructions](https://github.com/noplanman/php-telegram-bot#instructions) first, to understand what this library does exactly.

Installation and usage is pretty straight forward:

### Require this package with [Composer](https://getcomposer.org/)

Either run this command in your command line:

```
composer require noplanman/telegram-bot-manager
```

**or**

For existing Composer projects, edit your project's `composer.json` file to require `noplanman/telegram-bot-manager`:

```js
"require": {
    "noplanman/telegram-bot-manager": "*"
}
```
and then run `composer update`

**NOTE:** This will automatically also install PHP Telegram Bot into your project (if it isn't already).

### Performing actions

What use would this library be if you couldn't perform any actions?!

There are 3 parameters available to get things rolling:

Parameter | Description
----------|------------
s         | **s**ecret: This is a special secret value defined in the main `manager.php` file.
          | This parameter is required to call the script via browser!
a         | **a**ction: The actual action to perform. (handle (default), set, unset, reset)
          | **handle** executes the `getUpdates` method; **set** / **unset** / **reset** the Webhook.
l         | **l**oop: Number of seconds to loop the script for (used for getUpdates method).
          | This would be used mainly via CLI, to continually get updates for a certain period.

#### via browser

Simply point your browser to the `manager.php` file with the necessary **GET** parameters:
`http://example.com/manager.php?s=<secret>&a=<action>&l=<loop>`

*e.g.* Set the webhook:
`http://example.com/manager.php?s=super_secret&a=set`

*e.g.* Handle updates for 30 seconds:
`http://example.com/manager.php?s=super_secret&a=handle&l=30` or simply
`http://example.com/manager.php?s=super_secret&l=30` (`handle` action is the default)

#### via CLI

When using CLI, the secret is not necessary (since it could just be read from the file itself).

Call the `manager.php` file directly using `php` and pass the parameters:
`$ php manager.php a=<action> l=<loop>`

*e.g.* Set the webhook:
`$ php manager.php a=set`

*e.g.* Handle updates for 30 seconds:
`$ php manager.php a=handle l=30` or simply
`$ php manager.php l=30` (`handle` action is the default)

### Create the manager PHP file

You can name this file whatever you like, it just has to be somewhere inside your PHP project (preferably in the root folder to make things easier).
(Let's assume our file is called `manager.php`)

Let's start off with a simple example that uses the Webhook method:
```php
<?php

use NPM\TelegramBotManager\BotManager;

// Load composer.
require __DIR__ . '/vendor/autoload.php';

try {
    $bot = new BotManager([
        // Vitals!
        'api_key' => 'my_api_key',
        'botname' => 'my_own_bot',
        'secret'  => 'super_secret',

        // Extras.
        'webhook' => 'https://example.com/manager.php',
    ]);
    $bot->run();
} catch (\Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
```

### Set vital bot parameters

The vital parameters are:
- Bot API key
- Bot name
- A secret

What secret you ask? Well, this is a user-defined key that is required to execute any of the library features.
Best make it long, random and very unique!

For 84 random characters:
- If you have `pwgen` installed, just execute `pwgen 84` and choose any one.
- Or just go [here](https://www.random.org/strings/?num=7&len=12&digits=on&upperalpha=on&loweralpha=on&unique=on&format=plain&rnd=new) and put all the output onto a single line.

(You get 2 guesses why 84 is a good number :wink:)

### Set extra bot parameters

Apart from the necessary vital parameters, the bot can be easily configured using extra parameters.

Enable admins? Add custom command paths? Set up logging?
--> All no problem!

Here is a list of available extra parameters:

Parameter       | Description
---------       |------------
webhook         | URL to the manager PHP file used for setting up the Webhook.
                | *e.g.* `'https://example.com/manager.php'`
selfcrt         | Path to a self-signed certificate (if necessary).
                | *e.g.* `__DIR__ . '/server.crt'`
logging         | Path(s) where to the log files should be put. This is an array that can contain all 3 log file paths (`error`, `debug` and `update`).
                | *e.g.* `['error' => __DIR__ . '/php-telegram-bot-error.log']`
admins          | An array of user ids that have admin access to your bot.
                | *e.g.* `[12345]`
mysql           | Mysql credentials to connect a database (necessary for [`getUpdates`](#using-getupdates-method) method!).
                | *e.g.* `['host' => '127.0.0.1', 'user' => 'root', 'password' => 'root', 'database' => 'telegram_bot']`
download_path   | Custom download path.
                | *e.g.* `__DIR__ . '/Download'`
upload_path     | Custom upload path.
                | *e.g.* `__DIR__ . '/Upload'`
commands_paths  | A list of custom commands paths.
                | *e.g.* `[__DIR__ . '/CustomCommands']`
command_configs | A list of all custom command configs.
                | *e.g.* `['sendtochannel' => ['your_channel' => '@my_channel']`
botan_token     | The Botan.io token to be used for analytics.
                | *e.g.* `'botan_12345'`
custom_input    | Override the custom input of your bot (mostly for testing purposes!).
                | *e.g.* `'{"some":"raw", "json":"update"}'`

### Using getUpdates method

Using the `getUpdates` method requires a MySQL database connection:
```php
$bot = new BotManager([
    ...
    // Extras.
    'mysql'   => [
        'host'     => '127.0.0.1',
        'user'     => 'root',
        'password' => 'root',
        'database' => 'telegram_bot',
    ],
]);
```

Now, the updates can be done either through the [browser](#via-browser) or [via CLI](#via-cli).
