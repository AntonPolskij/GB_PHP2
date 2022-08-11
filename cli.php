<?php

require_once  __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Commands\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\{User, Post, Comment, UUID};
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\InMemoryUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

// $repo = new SqliteUsersRepository($connection);
// $repo = new SqlitePostsRepository($connection);
$repo = new SqliteCommentsRepository($connection);



$faker = Faker\Factory::create('ru-RU');

// $repo->save(new Post(UUID::random(),UUID::random(),'Zagolovok',"text111"));
// echo $repo->get(new UUID('a37e5634-ab9d-4a36-bd93-daaae1349d34'));

// $repo->save(new Comment(UUID::random(), UUID::random(), UUID::random(),'comment111'));
echo $repo->get(new UUID('f992b6e9-4105-470c-a2aa-9a2c79b8dca6'));