<?php

namespace SendMessages\Commands;

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\Update;
use SendMessages\Services\KeyboardHelper;

class StartCommand extends AbstractCommand
{
public function execute() {
    $keyboard = new KeyboardHelper();

    $keyboard->createButton('Ученик', 'pupilType', [
        'username' => $this->update->getMessage()->getChat()->getUsername()
    ]);
    $keyboard->createButton('Родитель', 'parentType', [
        'username' => $this->update->getMessage()->getChat()->getUsername()
    ]);


    Request::sendMessage([
        'chat_id' => $this->update->getMessage()->getChat()->getId(),
        'text' => 'Этот бот пересылает сообщения из определенных чатов, выберите, насчет чего вам присылать сообщения',
        'reply_markup' => $keyboard->getAllButtons()
    ]);
}

}