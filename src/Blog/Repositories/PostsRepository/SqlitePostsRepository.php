<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use PDO;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;


class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(Post $post): void {
      $statement = $this->connection->prepare('INSERT INTO posts (id, user_id, heading, text) VALUES (:id, :user_id, :heading, :text)');
      $statement->execute([
        ':id' => $post->getId(),
        ':user_id' => $post->getUser_id(),
        ':heading' => $post->getHeading(),
        ':text' => $post->getText(),
      ]);
    }

    public function get(UUID $id): ?Post {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new PostNotFoundException(
                "Cannot find Post: $id"
            );
        }
        return new Post(
            new UUID($result['id']),
            new UUID($result['user_id']),
            $result['heading'],
            $result['text'],
        );
    }
}