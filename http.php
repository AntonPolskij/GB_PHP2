<?php

use Psr\Log\LoggerInterface;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Auth\LogIn;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Http\Actions\FindByUsername;
use GeekBrains\LevelTwo\Blog\Http\Actions\Likes\CreateLike;
use GeekBrains\LevelTwo\Blog\Http\Actions\Posts\CreatePost;

use GeekBrains\LevelTwo\Blog\Http\Actions\Posts\DeletePost;
use GeekBrains\LevelTwo\Blog\Http\Actions\Comments\CreateComment;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);

$logger = $container->get(LoggerInterface::class);

try {
    // Пытаемся получить путь из запроса
    $path = $request->path();
} catch (HttpException) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}




$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class
    ],
    'POST' => [
        '/login' => LogIn::class,
        '/posts/create' => CreatePost::class,
        '/posts/comment' => CreateComment::class,
        '/posts/like' => CreateLike::class,
    ],
    'DELETE' => [
        '/posts' => DeletePost::class,
    ],
];



if (
    !array_key_exists($method, $routes)
    || !array_key_exists($path, $routes[$method])
) {
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}



$actionClassName = $routes[$method][$path];



try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse)->send();
    return;
}

$response->send();
