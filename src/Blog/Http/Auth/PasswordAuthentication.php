<?php

namespace GeekBrains\LevelTwo\Blog\Http\Auth;

use InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Exceptions\AuthException;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class PasswordAuthentication implements PasswordAuthenticationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }
    public function user(Request $request): User
    {
        try {

            $username = $request->jsonBodyField('username');
        } catch (HttpException | InvalidArgumentException $e) {

            throw new AuthException($e->getMessage());
        }
        try {

            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {

            throw new AuthException($e->getMessage());
        }
        try {
            $password = $request->jsonBodyField('password');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }   

        if (!$user->checkPassword($password)) {
            // Если пароли не совпадают — бросаем исключение
            throw new AuthException('Wrong password');
        }
        // Пользователь аутентифицирован
        return $user;
    }
}
