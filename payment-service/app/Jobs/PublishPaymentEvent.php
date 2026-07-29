<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PublishPaymentEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    public function backoff(): array
    {
        return [5, 10, 30, 60, 120];
    }

    public function __construct(
        public string $eventType,
        public array  $payload
    ) {}

    public function handle(): void
    {
        try {
            $queueName = "payment.{$this->eventType}";

            $connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.hosts.0.host',
                       '127.0.0.1'),
                config('queue.connections.rabbitmq.hosts.0.port',
                       5672),
                config('queue.connections.rabbitmq.hosts.0.user',
                       'guest'),
                config('queue.connections.rabbitmq.hosts.0.password',
                       'guest'),
                config('queue.connections.rabbitmq.hosts.0.vhost',
                       '/')
            );

            $channel = $connection->channel();

            $channel->queue_declare(
                $queueName,
                false,
                true,  // durable
                false,
                false
            );

            $msg = new AMQPMessage(
                json_encode($this->payload),
                ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
            );

            $channel->basic_publish($msg, '', $queueName);

            $channel->close();
            $connection->close();

            Log::info("Event {$this->eventType} published ke RabbitMQ",
                      $this->payload);

        } catch (\Exception $e) {
            Log::error("Gagal publish {$this->eventType}: " .
                       $e->getMessage());
            throw $e;
        }
    }
}