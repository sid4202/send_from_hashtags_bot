<?php

namespace SendMessages\Handlers;

use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use SendMessages\Commands\ParentTypeCallbackCommand;
use SendMessages\Commands\PupilTypeCallbackCommand;
use SendMessages\Commands\StartCommand;
use SendMessages\Models\User;

class MessageHandler
{
    public function handleUpdate(Update $update)
    {
        $message = $update->getMessage();

        $isPost = ($update->getChannelPost() !== null);

        if ($message !== null)
        {
            $this->handleMessage($message, $update);
        }

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

        if ($update->getCallbackQuery() !== null)
        {
            $this->handleCallback($update);
        }
    }

    private function handleMessage(Message $message, Update $update)
    {
        $text = $message->getText();

        if ($text = '/start')
        {
            $command = new StartCommand();

            $command->setUpdate($update);
            $command->execute();
        }
    }

    private function handleCallback(Update $update): void
    {
        $callback_data = json_decode($update->getCallbackQuery()->getData(), true);
        if ($callback_data['callback'] === 'pupilType') {
            $callbackCommand = new PupilTypeCallbackCommand();
        } elseif ($callback_data['callback'] === 'parentType') {
            $callbackCommand = new ParentTypeCallbackCommand();
        }

        $callbackCommand->setUpdate($update);
        $callbackCommand->parseCallbackData();
        $callbackCommand->execute();
    }
}