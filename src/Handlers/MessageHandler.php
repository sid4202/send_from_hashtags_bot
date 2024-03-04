<?php

namespace SendMessages\Handlers;

use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use SendMessages\Models\User;

class MessageHandler
{
    public function handleUpdate(Update $update)
    {
        $message = $update->getMessage();

        $isPost = ($update->getChannelPost() !== null);

        if ($isPost)
        {
            $users = User::all();

            foreach ($users as $user)
            {
               $chatId = $user->getUserChat()->first()->chat_id;

               Request::forwardMessage([
                   'chat_id' => $chatId,
                   'from_chat_id' => $update->getChannelPost()->getChat()->getId(),
                   'message_id' => $update->getChannelPost()->getMessageId()
               ]);
            }
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