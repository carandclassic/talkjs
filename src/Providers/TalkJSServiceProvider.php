<?php

declare(strict_types=1);

namespace CarAndClassic\TalkJS\Providers;

use CarAndClassic\TalkJS\TalkJSClient;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class TalkJSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/talkjs.php',
            'talkjs'
        );

        $this->app->singleton(
            TalkJSClient::class,
            static function ($app, $params) {
                $appId = $params['appId'] ?? config('talkjs.app_id');
                $secretKey = $params['secretKey'] ?? config('talkjs.secret_key');

                if ($appId === null || $secretKey === null) {
                    throw new BindingResolutionException(
                        'Cannot create TalkJs Client - either $appId or $secretKey were not populated'
                    );
                }

                return new TalkJSClient($appId, $secretKey);
            }
        );
    }

    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__ . "/../../config/talkjs.php" => config_path('talkjs.php')
            ]
        );
    }
}
