<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use PDO;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;


class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare('INSERT INTO users (id, username, first_name, last_name) VALUES (:id, :username, :first_name, :last_name)');
        $statement->execute([
            ':username' => $user->getUsername(),
            ':first_name' => $user->getName(),
            ':last_name' => $user->getSurname(),
            ':id' => (string)$user->getId()
        ]);
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
            throw new UserNotFoundException(
                "Cannot get user: $id"
            );
        }
        return new User(
            new UUID($result['id']),
            $result['username'],
            $result['first_name'],
            $result['last_name'],
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
            throw new UserNotFoundException("User not found: $username");
        }
        return  new User(
            new UUID($result['id']),
            $result['username'],
            $result['first_name'],
            $result['last_name'],
        );
    }
}
