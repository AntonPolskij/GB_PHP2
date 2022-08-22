<?php

use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Http\Actions\FindByUsername;
use GeekBrains\LevelTwo\Blog\Http\Actions\Posts\FindById;
use GeekBrains\LevelTwo\Blog\Http\Actions\Likes\CreateLike;
use GeekBrains\LevelTwo\Blog\Http\Actions\Posts\CreatePost;
use GeekBrains\LevelTwo\Blog\Http\Actions\Posts\DeletePost;
use GeekBrains\LevelTwo\Blog\Http\Actions\Comments\CreateComment;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);

try {
    // Пытаемся получить путь из запроса
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {

    (new ErrorResponse)->send();
    return;
}




$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class
    ],
    'POST' => [
        '/posts/create' => CreatePost::class,
        '/posts/comment' => CreateComment::class,
        '/posts/like' => CreateLike::class,
    ],
    'DELETE' => [
        '/posts' => DeletePost::class,
    ],
];



if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}


$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {

    $response = $action->handle($request);
    $response->send();
} catch (Exception $e) {

    (new ErrorResponse($e->getMessage()))->send();
}
