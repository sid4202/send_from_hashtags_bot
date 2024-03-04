<?php

namespace SendMessages\Commands;
abstract class AbstractCommand
{
    protected Update $update;

    public function setUpdate(Update $update)
    {
        $this->update = $update;
    }
}