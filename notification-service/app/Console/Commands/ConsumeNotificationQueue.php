<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPembayaranBerhasil;
use App\Jobs\ProcessPembayaranDitolak;
use App\Jobs\ProcessPembayaranPending;
use App\Jobs\ProcessTagihanDibuat;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ConsumeNotificationQueue extends Command
{
    protected $signature   = 'campuspay:consume';
    protected $description = 'Consume events dari RabbitMQ dan kirim notifikasi';

    public function handle(): void
    {
        $this->info('🚀 Notification Consumer started — mendengarkan event RabbitMQ...');

        try {
            $connection = new AMQPStreamConnection(
                config('queue.connections.rabbitmq.hosts.0.host'),
                config('queue.connections.rabbitmq.hosts.0.port'),
                config('queue.connections.rabbitmq.hosts.0.user'),
                config('queue.connections.rabbitmq.hosts.0.password'),
                config('queue.connections.rabbitmq.hosts.0.vhost'),
            );

            $channel = $connection->channel();

            // Daftarkan semua queue yang didengarkan
            $queues = [
                'payment.pembayaran_berhasil' => ProcessPembayaranBerhasil::class,
                'payment.pembayaran_pending'  => ProcessPembayaranPending::class,
                'payment.pembayaran_ditolak'  => ProcessPembayaranDitolak::class,
                'billing.tagihan_dibuat'      => ProcessTagihanDibuat::class,
            ];

            foreach ($queues as $queueName => $jobClass) {
                $channel->queue_declare(
                    $queueName,
                    false, // passive
                    true,  // durable — queue tetap ada setelah restart
                    false, // exclusive
                    false  // auto-delete
                );

                $channel->basic_consume(
                    $queueName,
                    '',
                    false,
                    false,
                    false,
                    false,
                    function (AMQPMessage $msg) use ($jobClass, $queueName) {
                        try {
                            $payload = json_decode($msg->body, true);
                            $this->info("📨 Event diterima dari [{$queueName}]");

                            // Dispatch job yang sesuai
                            dispatch(new $jobClass($payload));

                            $msg->ack(); // Konfirmasi message sudah diproses
                            $this->info("✅ Event berhasil diproses");

                        } catch (\Exception $e) {
                            $this->error("❌ Error: " . $e->getMessage());
                            $msg->nack(false, true); // Requeue jika gagal
                        }
                    }
                );

                $this->info("👂 Mendengarkan queue: {$queueName}");
            }

            // Loop terus — terus mendengarkan sampai dihentikan
            while ($channel->is_consuming()) {
                $channel->wait();
            }

            $channel->close();
            $connection->close();

        } catch (\Exception $e) {
            $this->error('Gagal connect ke RabbitMQ: ' . $e->getMessage());
            $this->info('Pastikan RabbitMQ sudah berjalan');
        }
    }
}