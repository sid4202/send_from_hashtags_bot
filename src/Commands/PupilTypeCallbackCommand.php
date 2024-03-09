<?php

namespace SendMessages\Commands;

use Longman\TelegramBot\Request;
use SendMessages\Commands\AbstractCallbackCommand;
use SendMessages\Models\User;
use SendMessages\Models\UserType;

class PupilTypeCallbackCommand extends AbstractCallbackCommand
{
    public function execute()
    {
        $userId = User::query()->get()->where('username', '=', $this->callbackData['data']['username'])->first()->id;

        UserType::query()->create([
            'user_id' => $userId,
            'type' => 'pupil'
        ]);

        Request::sendMessage([
            'text' => 'Теперь вам будут приходиить сообщения по #ученик',
            'chat_id' => $this->update->getCallbackQuery()->getMessage()->getChat()->getId()
        ]);
    }
}