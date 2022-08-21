<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\CommandException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepo,
    ) {
    }
    public function handle(Arguments $arguments): void
    {

        $username = $arguments->get('username');

        if ($this->userExists($username)) {
            throw new CommandException("User already exist: $username");
        }

        $this->usersRepo->save(new User(
            UUID::random(),
            $username,
            $arguments->get('first_name'),
            $arguments->get('last_name'),
        ));
    }

 

    private function userExists(string $username): bool
    {
        try {
            $this->usersRepo->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}
