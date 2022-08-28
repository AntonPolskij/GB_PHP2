<?php

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';
$command = $container->get(CreateUserCommand::class);
// Получаем объект логгера из контейнера
$logger = $container->get(LoggerInterface::class);
try {
    $command->handle(Arguments::fromArgv($argv));
} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
}
