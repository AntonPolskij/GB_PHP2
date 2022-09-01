<?php

namespace GeekBrains\LevelTwo\Blog\Commands;

use Psr\Log\LoggerInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\CommandException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepo,
        private LoggerInterface $logger,
    ) {
    }
    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");


        $username = $arguments->get('username');


        if ($this->userExists($username)) {

            $this->logger->warning("User already exists: $username");
            return;
            // throw new CommandException("User already exist: $username");
        }

        $user = User::createFrom(
            $username,
            $arguments->get('first_name'),
            $arguments->get('last_name'),
            $arguments->get('password'),
        );


        $this->usersRepo->save($user);

        $this->logger->info("User created: " . $user->getId());
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
