<?php

namespace SendMessages\Commands;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use SendMessages\Commands\Models\User;
use SendMessages\Services\KeyboardHelper;

class ChangeUserTypeCommand extends AbstractCallbackCommand
{
    public function execute()
    {
        $username = $this->callbackData['data']['username'];

        var_dump($username);

        $keyboard = new KeyboardHelper();

        $keyboard->createButton('Ученик', 'pupilTypeChange', [
            //'username' => $username
        ]);
        $keyboard->createButton('Родитель', 'parentTypeChange', [
            //'username' => $username
        ]);

        try {
            Request::sendMessage([
                'text' => 'Выберите хэштег, по которому вам будут приходить новостные посты',
                'chat_id' => $this->update->getCallbackQuery()->getMessage()->getChat()->getId(),
                'reply_markup' => $keyboard->getAllButtons()
            ]);
        } catch (TelegramException $e) {
            echo $e->getMessage();
        }
    }
}