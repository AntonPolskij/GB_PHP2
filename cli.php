<?php

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application;
use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Commands\Posts\DeletePost;
use GeekBrains\LevelTwo\Blog\Commands\Users\CreateUser;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Commands\FakeData\PopulateDB;
use GeekBrains\LevelTwo\Blog\Commands\Users\UpdateUser;

$container = require __DIR__ . '/bootstrap.php';
// $command = $container->get(CreateUserCommand::class);
// // Получаем объект логгера из контейнера
// $logger = $container->get(LoggerInterface::class);
// try {
//     $command->handle(Arguments::fromArgv($argv));
// } catch (Exception $e) {
//     $logger->error($e->getMessage(), ['exception' => $e]);
// }

// Создаём объект приложения
$application = new Application();
// Перечисляем классы команд
$commandsClasses = [
    CreateUser::class,
    DeletePost::class,
    UpdateUser::class,
    PopulateDB::class,
];
foreach ($commandsClasses as $commandClass) {
    // Посредством контейнера
    // создаём объект команды
    $command = $container->get($commandClass);
    // Добавляем команду к приложению
    $application->add($command);
}
// Запускаем приложение
$application->run();
