<?php

namespace SendMessages\Services;

require_once __DIR__.'/../../vendor/autoload.php';

use Longman\TelegramBot\Request;
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
            'host'     => $_ENV('DATABASE_HOST'),
            'user'     => $_ENV('DATABASE_USERNAME'),
            'password' => $_ENV('DATABASE_PASSWORD'),
            'database' => $_ENV('DATABASE'),
        ];

        try {
            $telegram = new Telegram($botApiKey, $botUsername);
            $telegram->enableMySql($mysql_credentials);

            //$handler = new MessageHandler();
            echo "Bot is running\n";
            while (true) {
                try {

                    $response = $telegram->handleGetUpdates();
                    $result = $response->getResult();

                    foreach ($result as $update) {
                        $chat = $update->getMessage()->getChat();

                        Request::sendMessage([
                            'chat_id' => $chat->getId(),
                            'text' => 'Hello world!!!'
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

}
