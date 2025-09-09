<?php
declare(strict_types=1);

namespace Bazegel\Monobank\Providers;

use Illuminate\Support\ServiceProvider;
use Bazegel\Monobank\Acquiring;
use Bazegel\Monobank\MonobankClient;
use Bazegel\Monobank\Services\MonobankService;

class MonobankServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MonobankService::class, function () {
            $httpClient = new \GuzzleHttp\Client([
                'base_uri' => config('monobank.url'),
                'headers' => [
                    'X-Token' => config('monobank.token'),
                ]
            ]);
            $monobankClient = new MonobankClient($httpClient);
            $acquiring = new Acquiring($monobankClient);

            return new MonobankService($acquiring);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/monobank.php' => config_path('monobank.php'),
        ], 'monobank-config');
    }
}
