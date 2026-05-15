# mmi-event-consumer

PHP library providing a message consumer abstraction for AMQP-compatible message brokers (RabbitMQ), built on the MMi Framework.

## Features

- Clean `MessageConsumerInterface` for consuming messages
- AMQP implementation (`AmqpMessageConsumer`) using `php-amqplib`
- Null implementation (`NullMessageConsumer`) for testing/disabling
- PHP-DI integration with environment variable configuration

## Requirements

- PHP 8.4 or 8.5
- Composer
- `ext-amqp` PHP extension

## Installation

```bash
composer require mmi/mmi-event-consumer
```

## Quick Start

1. Include the DI configuration in your application:

```php
// In your PHP-DI config
return [
    // ... your config
    ...include 'vendor/mmi/mmi-event-consumer/src/MmiEventConsumer/di.consumer.php',
];
```

2. Set environment variables:

```env
MMI_CONSUMER_QUEUE_ENABLED=true
MMI_CONSUMER_QUEUE_HOST=rabbitmq
MMI_CONSUMER_QUEUE_PORT=5672
MMI_CONSUMER_QUEUE_VHOST=/
MMI_CONSUMER_QUEUE_USERNAME=guest
MMI_CONSUMER_QUEUE_PASSWORD=guest
```

3. Inject and use:

```php
use MmiEventConsumer\MessageConsumerInterface;

class MyService
{
    public function __construct(
        private MessageConsumerInterface $consumer
    ) {}

    public function start(): void
    {
        $this->run(function ($message) {
            // Process message
        });
    }
}
```

## Configuration

| Variable | Default | Description |
|----------|---------|-------------|
| `MMI_CONSUMER_QUEUE_ENABLED` | `false` | Enable/disable the AMQP consumer |
| `MMI_CONSUMER_QUEUE_HOST` | `localhost` | RabbitMQ host |
| `MMI_CONSUMER_QUEUE_PORT` | `5672` | RabbitMQ port |
| `MMI_CONSUMER_QUEUE_VHOST` | `''` | RabbitMQ virtual host |
| `MMI_CONSUMER_QUEUE_USERNAME` | `''` | RabbitMQ username |
| `MMI_CONSUMER_QUEUE_PASSWORD` | `''` | RabbitMQ password |

## Development

### Setup

```bash
git clone <repository-url>
cd mmi-event-consumer
composer install
```

### Docker

```bash
docker build --build-arg PHP_VERSION=8.5 -t mmi-event-consumer .
docker run mmi-event-consumer sh -c "composer test:all"
```

### Testing

```bash
# Run all checks (security, PHPCS, PHPStan, PHPMD, PHPUnit)
composer test:all

# Run PHPUnit only
composer test:phpunit

# Static analysis (level 8)
composer test:phpstan

# Code style check
composer test:phpcs

# Mess detection
composer test:phpmd

# Security check
composer test:security-checker
```

### Code Fixing

```bash
# Run all fixers
composer fix:all

# PHP Code Beautifier
composer fix:phpcbf

# PHP-CS-Fixer
composer fix:php-cs-fixer
```

### Reports

```bash
# Generate PHPMetrics HTML report
composer report:metrics
# Output: web/build/metrics/
```

Coverage reports are generated in:
- HTML: `web/build/phpunit/`
- Clover: `.phpunit.coverage.clover.xml`

## Code Quality Standards

- **Coding Standard**: PSR2 (PHP_CodeSniffer)
- **Static Analysis**: PHPStan level 8
- **Mess Detection**: PHPMD (Clean Code, Code Size, Design, etc.)
- **Security**: Dependency vulnerability checking

## CI/CD

GitHub Actions runs on pull requests and pushes:
- Tests against PHP 8.4 and 8.5
- Builds Docker image and runs `composer test:all`

## Project Structure

```
src/MmiEventConsumer/
├── AmqpMessageConsumer.php      # RabbitMQ consumer implementation
├── MessageConsumerInterface.php # Consumer interface
├── NullMessageConsumer.php      # Null object implementation
└── di.consumer.php              # PHP-DI configuration

tests/
└── Unit/MmiEventConsumer/
    └── NullMessageConsumerTest.php
```
