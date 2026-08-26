<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
        
    }

   
    public function boot(): void
    {
        if (config('app.env') === 'production') {
        URL::forceScheme('https');

        \Illuminate\Support\Facades\Log::info('Brevo key check', [
            'is_set' => !empty(config('services.brevo.key')),
            'length' => strlen((string) config('services.brevo.key')),
        ]);

        \Illuminate\Support\Facades\Log::info('Cloudinary URL check', [
    'is_set' => !empty(env('CLOUDINARY_URL')),
    'length' => strlen((string) env('CLOUDINARY_URL')),
]);

        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory())->create(
                new Dsn('brevo+api', 'default', config('services.brevo.key'))
            );
        });
       }

    }
}
