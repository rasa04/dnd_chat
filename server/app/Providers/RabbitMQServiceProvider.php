<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Queue\QueueInterface;
use App\Services\Queue\RabbitService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

final class RabbitMQServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(
            AMQPStreamConnection::class,
            static function (): AMQPStreamConnection
            {
                $config = config('queue.connections.rabbit');

                return new AMQPStreamConnection(
                    $config['host'],
                    $config['port'],
                    $config['user'],
                    $config['password']
                );
            }
        );

        $this->app->singleton(
            AbstractChannel::class,
            static fn (Application $app): AbstractChannel => $app->make(AMQPStreamConnection::class)->channel()
        );

        $this->app->singleton(
            QueueInterface::class,
            static fn (Application $app): QueueInterface => new RabbitService($app->make(AbstractChannel::class))
        );
    }

    public function provides(): array
    {
        return [
            AMQPStreamConnection::class,
            AbstractChannel::class,
            QueueInterface::class,
        ];
    }
}
