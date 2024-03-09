<?php

namespace SendMessages\Commands;

use Longman\TelegramBot\Entities\CallbackQuery;

abstract class AbstractCallbackCommand extends AbstractCommand
{
    protected array $callbackData;
    public function parseCallbackData()
    {
        $this->callbackData = json_decode($this->update->getCallbackQuery()->getData(), true);
    }

}