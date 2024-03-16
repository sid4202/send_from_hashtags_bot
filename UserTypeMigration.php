<?php

require_once './vendor/autoload.php';

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

$kernel = new SendMessages\Services\Kernel();
$kernel->connectDatabase();

Manager::schema()->create('user_type', function (Blueprint $table) {
    $table->bigInteger('user_id');
    $table->string('type');
});