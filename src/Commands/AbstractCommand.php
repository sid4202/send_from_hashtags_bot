<?php

namespace SendMessages\Commands;

use Longman\TelegramBot\Entities\Update;
abstract class AbstractCommand
{
    protected Update $update;

    public function setUpdate(Update $update)
    {
        $this->update = $update;
    }
}