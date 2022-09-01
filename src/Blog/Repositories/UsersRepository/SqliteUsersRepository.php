<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use PDO;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use Psr\Log\LoggerInterface;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    ) {
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare('INSERT INTO users (id, password, username, first_name, last_name) VALUES (:id, :password, :username, :first_name, :last_name ) ON CONFLICT (id) DO UPDATE SET first_name = :first_name, last_name = :last_name');

        $userId = (string)$user->getId();

        $statement->execute([
            ':username' => $user->getUsername(),
            ':password' => $user->hashedPassword(),
            ':first_name' => $user->getName(),
            ':last_name' => $user->getSurname(),
            ':id' => $userId
        ]);


        $this->logger->info("New User UUID:$userId saved in database");
    }

    public function getById(UUID $id): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE id = :id'
        );
        $statement->execute([
            ':id' => (string)$id,
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {

            $this->logger->warning("Cannot get user: $id");
            throw new UserNotFoundException(
                "Cannot get user: $id"
            );
        }
        return new User(
            new UUID($result['id']),
            $result['username'],
            $result['first_name'],
            $result['last_name'],
            $result['password'],
        );
    }

    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            $this->logger->warning("Cannot get user: $username");
            throw new UserNotFoundException("User not found: $username");
        }
        return  new User(
            new UUID($result['id']),
            $result['username'],
            $result['first_name'],
            $result['last_name'],
            $result['password'],
        );
    }
}
