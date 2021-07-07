<?php


namespace CarAndClassic\TalkJS\Providers;

use CarAndClassic\TalkJS\TalkJSClient;
use Illuminate\Support\ServiceProvider;

class TalkJSServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/talkjs.php', 'talkjs'
        );
        $this->app->bind(TalkJSClient::class, static function($app, $params) {
            $appId = $params['appId'] ?? config('talkjs.app_id');
            $secretKey = $params['secretKey'] ?? config('talkjs.secret_key');
            return new TalkJSClient($appId, $secretKey);
        });
    }
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . "/../../config/talkjs.php" => config_path('talkjs.php')
        ]);
    }
}
