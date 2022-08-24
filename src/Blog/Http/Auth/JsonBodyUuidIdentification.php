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

class JsonBodyUuidIdentification implements IdentificationInterface
{
public function __construct(
private UsersRepositoryInterface $usersRepository
) {
}
public function user(Request $request): User
{
try {

$userId = new UUID($request->jsonBodyField('user_id'));
} catch (HttpException|InvalidArgumentException $e) {

throw new AuthException($e->getMessage());
}
try {

return $this->usersRepository->getById($userId);
} catch (UserNotFoundException $e) {

throw new AuthException($e->getMessage());
}
}
}