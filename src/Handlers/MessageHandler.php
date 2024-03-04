<?php

namespace SendMessages\Handlers;

use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
class MessageHandler
{
    public function handleUpdate(Update $update)
    {
        $message = $update->getMessage();

        if ($message !== null)
        {
            $this->handleMessage($message);
        }
    }

    private function handleMessage(Message $message)
    {
        $text = $message->getText();

        if ($text = '/start')
        {

        }
    }
}