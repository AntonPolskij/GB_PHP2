<?php

require_once  __DIR__ . '/vendor/autoload.php';

use GeekBrains\LevelTwo\Blog\{User, Post, Comment};

$faker = Faker\Factory::create('ru-RU');

$message = "Введите: 'user', 'post' или 'comment'";


if (empty($argv[1])) {
    die($message);
} else {
    $inputData = $argv[1];
}

$id = (int)$faker->uuid();
$name = $faker->firstName();
$surname = $faker->lastName();
$user = new User($id, $name, $surname);

$post = new Post((int)$faker->uuid(), $user, $faker->title(), $faker->text());

$comment = new Comment((int)$faker->uuid(), $user, $post, $faker->text());

switch ($inputData) {
    case "user":

        echo $user;

        break;

    case "post":

        echo $post;

        break;
    case "comment":

        echo $comment;

        break;
    default:
        echo $message;
        break;
}
