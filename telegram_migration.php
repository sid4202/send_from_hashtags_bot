<?php

require_once './vendor/autoload.php';

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

$kernel = new SendMessages\Services\Kernel();
$kernel->connectDatabase();

$rawQuery = file_get_contents(__DIR__.'/vendor/longman/telegram-bot/structure.sql');
$connection = Manager::connection();

foreach (explode(";\n", $rawQuery) as $query) {
    if (!empty($query)) {
        $connection->statement($query);
}
}



