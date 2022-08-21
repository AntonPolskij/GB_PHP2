<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;


use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\PostNotFoundException;


class SqlitePostsRepository implements PostsRepositoryInterface
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException|UserNotFoundException
     */
    public function get(UUID $id): Post
    {
        $statement = $this->connection->prepare(
            'SELECT *
             FROM posts LEFT JOIN users
                    ON posts.user_id = users.id 
                    WHERE posts.id = :id'
        );
        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getPost($statement, $id);
    }

    public function delete(UUID $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM posts WHERE id = :id');

        $statement->execute([
            'id' => (string)$id,
        ]);
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (id, user_id, heading, text) VALUES (:id, :user_id, :heading, :text)'
        );


        // Выполняем запрос с конкретными значениями
        $statement->execute([
            ':id' => (string)$post->getId(),
            ':user_id' => (string)$post->getUser_id(),
            ':heading' => $post->getHeading(),
            ':text' => $post->getText()
        ]);
    }


    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException
     * @throws UserNotFoundException
     */
    private function getPost(\PDOStatement $statement, string $postId): Post
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot find post: $postId"
            );
        }

        //$userRepository = new SqliteUsersRepository($this->connection);
        //$user = $userRepository->get(new UUID($result['author_uuid']));

        $user = new User(
            new UUID($result['user_id']),
            $result['username'],
            $result['first_name'],
            $result['last_name']
        );

        return new Post(
            new UUID($result['id']),
            $user,
            $result['heading'],
            $result['text']
        );
    }
}
