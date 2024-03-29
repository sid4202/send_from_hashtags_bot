<?php

namespace SendMessages\Handlers;

use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use SendMessages\Commands\ChangeUserTypeCommand;
use SendMessages\Commands\Models\UserChat;
use SendMessages\Commands\Models\UserType;
use SendMessages\Commands\ParentTypeCallbackCommand;
use SendMessages\Commands\ParentTypeChangeCallbackCommand;
use SendMessages\Commands\PupilTypeCallbackCommand;
use SendMessages\Commands\PupilTypeChangeCallbackCommand;
use SendMessages\Commands\StartCommand;
use SendMessages\Services\KeyboardHelper;
use SendMessages\Commands\Models\User;

class MessageHandler
{
    public function handleUpdate(Update $update)
    {
        $message = $update->getMessage();

        $isPost = ($update->getChannelPost() !== null);

        if ($message !== null) {
            $this->handleMessage($message, $update);
        }

        if ($isPost) {
            $this->handlePost($update);
        }

        if ($update->getCallbackQuery() !== null) {
            $this->handleCallback($update);
        }
    }

    private function handleMessage(Message $message, Update $update)
    {
        $text = $message->getText();

        if ($text == '/start') {
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
        } elseif ($callback_data['callback'] === 'changeUserType') {
            $callbackCommand = new ChangeUserTypeCommand();
        } elseif ($callback_data['callback'] === 'parentTypeChange') {
            $callbackCommand = new ParentTypeChangeCallbackCommand();
        } elseif ($callback_data['callback'] === 'pupilTypeChange') {
            $callbackCommand = new PupilTypeChangeCallbackCommand();
        }

        $callbackCommand->setUpdate($update);
        $callbackCommand->parseCallbackData();
        $callbackCommand->execute();
    }

    private function handlePost(Update $update)
    {
        $post = $update->getChannelPost();
        var_dump($post->getText());

        if (str_contains($post->getText(), '#ученик'))
            $type = 'pupil';
        elseif (str_contains($post->getText(), '#родитель'))
            $type = 'parent';
        else
            return;


        $users = UserType::query()
            ->get()
            ->where('type', '=', $type);

        foreach ($users as $user) {

            $chatId = UserChat::query()->get()->where('user_id', '=', $user->user_id)->first()->chat_id;

            $keyboard = new KeyboardHelper();

            $keyboard->createButton("Сменить", "changeUserType", [
                'username' => User::query()
                ->get()
                ->where('id', '=', $user->user_id)->first()
                ->username
            ]);

            Request::forwardMessage([
                'chat_id' => $chatId,
                'from_chat_id' => $update->getChannelPost()->getChat()->getId(),
                'message_id' => $update->getChannelPost()->getMessageId(),
            ]);

            Request::sendMessage([
                'text' => "Хотите ли вы поменять хэштег, по которому вам приходят новостные посты, если да - нажните кнопку",
                'chat_id' => $chatId,
                'reply_markup' => $keyboard->getAllButtons()
            ]);
        }
    }
}