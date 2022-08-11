<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;

use PDO;
use GeekBrains\LevelTwo\Blog\UUID;

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;



class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    ) {
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare('INSERT INTO comments (id, user_id, post_id, text) VALUES (:id, :user_id, :post_id, :text)');
        $statement->execute([
            ':id' => $comment->getId(),
            ':user_id' => $comment->getUser_id(),
            ':post_id' => $comment->getPost_id(),
            ':text' => $comment->getText(),
        ]);
    }

    public function get(UUID $id): ?Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            throw new CommentNotFoundException(
                "Cannot find comment: $id"
            );
        }
        return new Comment(
            new UUID($result['id']),
            new UUID($result['user_id']),
            new UUID($result['post_id']),
            $result['text'],
        );
    }
}
