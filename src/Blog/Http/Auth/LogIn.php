<?php

namespace GeekBrains\LevelTwo\Blog\Http\Auth;

use DateTimeImmutable;
use GeekBrains\LevelTwo\Blog\AuthToken;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Exceptions\AuthException;
use GeekBrains\LevelTwo\Blog\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Repositories\TokensRepository\AuthTokensRepositoryInterface;

class LogIn implements ActionInterface
{
    public function __construct(
        // Авторизация по паролю
        private PasswordAuthenticationInterface $passwordAuthentication,
        // Репозиторий токенов
        private AuthTokensRepositoryInterface $authTokensRepository
    ) {
    }
    public function handle(Request $request): Response
    {
        // Аутентифицируем пользователя
        try {
            $user = $this->passwordAuthentication->user($request);
        } catch (AuthException $e) {
            return new ErrorResponse($e->getMessage());
        }
        // Генерируем токен
        $authToken = new AuthToken(
            // Случайная строка длиной 40 символов
            bin2hex(random_bytes(40)),
            $user->getId(),
            // Срок годности - 1 день
            (new DateTimeImmutable())->modify('+1 day')
        );
        // Сохраняем токен в репозиторий
        $this->authTokensRepository->save($authToken);
        // Возвращаем токен
        return new SuccessfulResponse([
            'token' => $authToken->token(),
        ]);
    }
}
