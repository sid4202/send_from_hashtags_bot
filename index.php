<?php

require_once __DIR__ . '/vendor/autoload.php';

use SendMessages\Services\Kernel;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$kernel = new Kernel();

$kernel->run();
