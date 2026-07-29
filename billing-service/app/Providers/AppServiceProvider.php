<?php

namespace App\Providers;

use App\Events\TagihanDibuat;
use App\Listeners\PublishTagihanDibuatToRabbitMQ;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Daftarkan event listener
        Event::listen(
            TagihanDibuat::class,
            PublishTagihanDibuatToRabbitMQ::class
        );
    }
}