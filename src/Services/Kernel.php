<?php

namespace SendMessages\Services;

require_once __DIR__.'/../../vendor/autoload.php';

use Dotenv\Dotenv;
use Longman\TelegramBot\Request;
use Illuminate\Database\Capsule\Manager as Capsule;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use Throwable;

class Kernel
{
    public function run(): void
    {

        $botUsername = $_ENV['BOT_USERNAME'];
        $botApiKey = $_ENV['BOT_API_KEY'];

        $mysql_credentials = [
            'host'     => $_ENV['DATABASE_HOST'],
            'user'     => $_ENV['DATABASE_USERNAME'],
            'password' => $_ENV['DATABASE_PASSWORD'],
            'database' => $_ENV['DATABASE'],
        ];

        $this->connectDatabase();

        try {
            $telegram = new Telegram($botApiKey, $botUsername);
            $telegram->enableMySql($mysql_credentials);

            $telegram->setCommandConfig('sendtochannel', [
                'your_channel' => [
                    '@devchannel2',
                ]
            ]);

            //$handler = new MessageHandler();
            echo "Bot is running\n";
            while (true) {
                try {

                    $response = $telegram->handleGetUpdates();
                    $result = $response->getResult();

                    foreach ($result as $update) {
                        $chat = $update->getMessage()->getChat();



                        Request::sendMessage([
                            "chat_id" => "@devchannel2",
                            'text' => 'FINALLY WORKS!!!!!!!!!!!'
                        ]);
                    }
                    sleep(1);

                } catch (Throwable $e) {
                    echo $e->getMessage();
                }
            }
        } catch (TelegramException $e) {
            echo $e->getMessage();
        }
    }


    public function connectDatabase(): void
    {
        if (empty($_ENV)) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        $capsule = new Capsule();

        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $_ENV['DATABASE_HOST'],
            'database' => $_ENV['DATABASE'],
            'username' => $_ENV['DATABASE_USERNAME'],
            'password' => $_ENV['DATABASE_PASSWORD'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

}
