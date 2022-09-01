<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;


use Psr\Log\LoggerInterface;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;


class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private \PDO $connection,
        private LoggerInterface $logger)
    {
        
    }

    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException|UserNotFoundException
     */
    public function get(UUID $id): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE id = :id'
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


        $postId = (string)$post->getId();

        $statement->execute([
            ':id' => $postId,
            ':user_id' => (string)$post->getUser_id(),
            ':heading' => $post->getHeading(),
            ':text' => $post->getText()
        ]);

        $this->logger->info("New Post UUID:$postId saved in database");
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
            $this->logger->warning("Cannot find post: $postId");
            throw new PostNotFoundException(
                "Cannot find post: $postId"
            );
        }

        $userRepository = new SqliteUsersRepository($this->connection, $this->logger);
        $user = $userRepository->getById(new UUID($result['user_id']));

        // $user = new User(
        //     new UUID($result['user_id']),
        //     $result['username'],
        //     $result['first_name'],
        //     $result['last_name']
        // );

        return new Post(
            new UUID($result['id']),
            $user,
            $result['heading'],
            $result['text']
        );
    }
}
