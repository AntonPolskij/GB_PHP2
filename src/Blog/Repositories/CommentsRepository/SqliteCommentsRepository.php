<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;

use PDO;
use Psr\Log\LoggerInterface;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Exceptions\CommentNotFoundException;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    ) {
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare('INSERT INTO comments (id, user_id, post_id, text) VALUES (:id, :user_id, :post_id, :text)');

        $commentId = (string)$comment->getId(); 

        $statement->execute([
            ':id' => $commentId,
            ':user_id' => (string)$comment->getUser_id(),
            ':post_id' => (string)$comment->getPost_id(),
            ':text' => $comment->getText()
        ]);

        $this->logger->info("New Comment UUID:$commentId saved in database");
    }

    public function get(UUID $id): ?Comment
    {
        // $statement = $this->connection->prepare('SELECT * FROM comments WHERE id = :id');

        $statement = $this->connection->prepare(
            'SELECT *
             FROM comments LEFT JOIN users
                    ON comments.user_id = users.id LEFT JOIN posts ON comments.post_id = posts.id
                    WHERE posts.id = :id'
        );

        $statement->execute([
            ':id' => (string)$id,
        ]);

        return $this->getComment($statement, $id);
    }

    public function getComment(\PDOStatement $statement, $id): Comment
    {

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {

            $this->logger->warning("Cannot find comment: $id");

            throw new CommentNotFoundException(
                "Cannot find comment: $id"
            );
        }

        // $userRepo = new SqliteUsersRepository($this->connection);
        // $user = $userRepo->getById(new UUID($result['user_id']));

        // $postRepo = new SqlitePostsRepository($this->connection);
        // $post = $postRepo->get(new UUID($result['post_id']));

        $user = new User(
            new UUID($result['user_id']),
            $result['username'],
            $result['first_name'],
            $result['last_name']
        );

        $post = new Post(
            new UUID($result['post_id']),
            $user,
            $result['heading'],
            $result['text']
        );


        return new Comment(
            new UUID($result['id']),
            $user,
            $post,
            $result['text'],
        );
    }
}
