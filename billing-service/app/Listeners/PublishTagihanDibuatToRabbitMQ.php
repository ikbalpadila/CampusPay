<?php

namespace App\Listeners;

use App\Events\TagihanDibuat;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PublishTagihanDibuatToRabbitMQ
{
    public function handle(TagihanDibuat $event): void
    {
        try {
            $tagihan = $event->tagihan;

            $payload = json_encode([
                'tagihan_id'  => $tagihan->id,
                'mahasiswa_id'=> $tagihan->mahasiswa_id,
                'nominal'     => $tagihan->nominal,
                'jenis'       => $tagihan->paymentType->nama
                                 ?? 'Pembayaran',
                'semester'    => $tagihan->semester_nama,
                'jatuh_tempo' => $tagihan->jatuh_tempo,
            ]);

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
                'billing.tagihan_dibuat',
                false,
                true,  // durable
                false,
                false
            );

            $msg = new AMQPMessage($payload, [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]);

            $channel->basic_publish(
                $msg,
                '',
                'billing.tagihan_dibuat'
            );

            $channel->close();
            $connection->close();

            Log::info('Event tagihan_dibuat published ke RabbitMQ',
                      ['tagihan_id' => $tagihan->id]);

        } catch (\Exception $e) {
            Log::error('Gagal publish tagihan_dibuat: ' . $e->getMessage());
        }
    }
}