<?php

namespace SendMessages\Commands;

use Longman\TelegramBot\Request;
use SendMessages\Commands\Models\User;
use SendMessages\Commands\Models\UserType;

class ParentTypeChangeCallbackCommand extends AbstractCallbackCommand
{
    public function execute()
    {
        $userId = User::query()
            ->get()
            ->where('username', '=', $this->update->getCallbackQuery()->getMessage()->getChat()->getUsername())
            ->first()
            ->id;

        UserType::query()
            ->where('user_id', '=', $userId)
            ->delete();

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