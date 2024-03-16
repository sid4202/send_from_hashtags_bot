<?php

namespace SendMessages\Commands;

use Longman\TelegramBot\Request;
use SendMessages\Commands\Models\User;
use SendMessages\Commands\Models\UserType;

class ParentTypeCallbackCommand extends AbstractCallbackCommand
{
    public function execute()
    {
        $userId = str(User::query()->get()->where('username', '=', $this->callbackData['data']['username'])->first()->id);

        UserType::query()->create([
            'user_id' => $userId,
            'type' => 'parent'
        ]);

        Request::sendMessage([
            'text' => 'Теперь вам будут приходиить сообщения по #родитель',
            'chat_id' => $this->update->getCallbackQuery()->getMessage()->getChat()->getId()
        ]);
    }
}