<?php

namespace SendMessages\Services;

class KeyboardHelper
{
    private array $keyboard = [
        'inline_keyboard' =>[]
    ];

    public function createButton(string $buttonText, string $callbackName, array $callbackData)
    {
        $this->keyboard['inline_keyboard'][] = [
            [
                'text' => $buttonText,
                'callback_data' => self::makeCallback($callbackName, $callbackData)
            ]
        ];
    }

    public function getAllButtonsButton(): string
    {
        return json_encode($this->keyboard);
    }

    public static function makeCallback(string $callbackName, array $callbackData): string
    {
        $callback = [
            'callback' => $callbackName,
            'data' => $callbackData,
        ];

        return json_encode($callback);
    }
}
