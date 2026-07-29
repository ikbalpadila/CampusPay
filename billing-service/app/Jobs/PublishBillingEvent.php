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

class PublishBillingEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $eventType,
        public array  $payload
    ) {}

    public function handle(): void
    {
        try {
            $queueName = "billing.{$this->eventType}";

            $connection = new AMQPStreamConnection(
                env('RABBITMQ_HOST', '127.0.0.1'),
                env('RABBITMQ_PORT', 5672),
                env('RABBITMQ_USER', 'guest'),
                env('RABBITMQ_PASSWORD', 'guest'),
                env('RABBITMQ_VHOST', '/')
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

            Log::info("Event billing.{$this->eventType} published ke RabbitMQ", $this->payload);

        } catch (\Exception $e) {
            Log::error("Gagal publish billing.{$this->eventType} ke RabbitMQ: " . $e->getMessage());
        }
    }
}
