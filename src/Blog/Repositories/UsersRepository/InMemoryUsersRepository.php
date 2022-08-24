<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;


class InMemoryUsersRepository implements UsersRepositoryInterface
{
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function getById(UUID $id): User
    {
        foreach ($this->users as $user) {
            if ((string)$user->getId() === (string)$id) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found id: $id");
    }

    public function getByUsername(string $username): User
    {
        foreach ($this->users as $user) {
            if ($user->getName() === $username) {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found : $username");
    }
}