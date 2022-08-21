<?php

require_once  __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\{User, Post, Comment, UUID};
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\InMemoryUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$container = require __DIR__ . '/bootstrap.php';
// При помощи контейнера создаём команду
$command = $container->get(CreateUserCommand::class);
try {
    $command->handle(Arguments::fromArgv($argv));
} catch (Exception $e) {
    echo "{$e->getMessage()}\n";
}
