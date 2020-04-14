[![MIT License][license-shield]][license-url]

# Logger
PSR-3 logger interface implementation inspired by monolog

## Requirements

`php > 7.3`

## Install
via Composer

```bash
$ composer require flotzilla/logger
```

## Description

## Basic usage
```php
$logger = new Logger();
$channel = new Channel('logs', [
        new FileHandler( new SimpleLineFormatter())
    ]);

$logger->addChannel($channel);

$logger->info('some data');
$logger->error('some message', ['data' => ['name' => 'John', 'job' => 'night watcher']]);
```

## Advanced usage

Initialization
```php
use \flotzilla\Logger\Channel\Channel;
use \flotzilla\Logger\Formatter\JsonFormatter;
use \flotzilla\Logger\Handler\ConsoleHandler;
use \flotzilla\Logger\Handler\FileHandler;
use \flotzilla\Logger\Logger;
use \flotzilla\Logger\LogLevel\LogLevel;
use \flotzilla\Logger\Formatter\PsrFormatter;
use \flotzilla\Logger\Formatter\SimpleLineFormatter;

$logger = new Logger();

//output will be written to /tmp/main-logs.log
$channel = new Channel('logs-all', [
        new FileHandler(new SimpleLineFormatter(), '/tmp', 'main-logs')
    ]);

// set log levels for this channel
$debugChannel = new Channel('debug', [
        new FileHandler(new SimpleLineFormatter(), 'tmp', 'debug'),
        new FileHandler(new PsrFormatter()) // /tmp dir by default
    ], LogLevel::DEBUG, LogLevel::NOTICE);

$criticalChannel = new Channel('critical-only', [
        new FileHandler(new SimpleLineFormatter(), '/tmp', 'critical'),
        new FileHandler(new JsonFormatter(), '/tmp', 'critical')
    ], LogLevel::CRITICAL, LogLevel::EMERGENCY);

// write output to console
$channel = new Channel('logs', [
        new ConsoleHandler(new SimpleLineFormatter())
    ]);

// add channels 
$logger->addChannel($channel);
$logger->addChannel($debugChannel);
$logger->addChannel($criticalChannel);

// or set them all 
$logger->setChannels([$channel, $debugChannel, $criticalChannel]);

// or pass to constructor
$logger = new Logger([$channel, $debugChannel, $criticalChannel]);
```

Channels
```php
// disable channel for writing 
$logger->getChannel('debug')->setEnabled(false);

// set maximal log level for filtering
$logger->getChannel('test')->setMaxLogLevel('error');

// set minimal log level for filtering
$logger->getChannel('test')->setMinLogLevel('info');
```

Set custom datetime format and timezone
```php
$logger = new Logger($channels, 'Y.j.m', new DateTimeZone('Europe/London'));
```

## Testing

```bash
$ composer test
$ composer test_mock
```

## Benchmarks
```bash
$ composer bench
```

## License

The MIT License (MIT). Please see [License File](https://github.com/flotzilla/logger/blob/master/LICENCE.md) for more information.

[license-shield]: https://img.shields.io/github/license/othneildrew/Best-README-Template.svg?style=flat-square
[license-url]: https://github.com/flotzilla/container/blob/master/LICENCE.md