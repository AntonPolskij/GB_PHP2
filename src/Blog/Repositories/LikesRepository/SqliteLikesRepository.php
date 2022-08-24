<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepository;

use Psr\Log\LoggerInterface;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\LikeAlreadyExists;
use GeekBrains\LevelTwo\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;

class SqliteLikesRepository implements LikesRepositoryInterface
{
    public function __construct(
        private \PDO $pdo,
        private LoggerInterface $logger
    )
    {
        
    }

    public function save(Like $like): void
    {
        $statement = $this->pdo->prepare('INSERT INTO likes (id, user_id, post_id) VALUES (:id, :user_id, :post_id)');

        $likeId = (string)$like;

        $statement->execute([
            ':id' => $likeId,
            'user_id' => (string)$like->getUser()->getId(),
            'post_id' => (string)$like->getPost()->getId(),
        ]);

        $this->logger->info("New Like UUID:$likeId saved in database");
    }

    /**
     * @throws LikeNotFoundException
     */
    public function getByPostId(UUID $id): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM likes WHERE post_id = :id');
        $statement->execute([
            'id' => $id
        ]);

        $result = $statement->fetchAll();

        if (!$result) {
            $this->logger->warning("Like not found to post" . $id);
            throw new LikeNotFoundException(
                'Like not found to post' . $id
            );
        }

        return $result;
    }

    /**
     * @throws LikeAlreadyExists
     */
    public function checkUserLikeForPostExists($postId,$userId): void
    {
        $statement = $this->pdo->prepare('SELECT * FROM likes WHERE post_id = :post_id AND user_id=:user_id');

        $statement->execute([
            ':post_id' => $postId,
            ':user_id' => $userId
        ]);

        $isExisted = $statement->fetch();

        if($isExisted){
            $this->logger->warning("The users like for this post already exists");
            throw new LikeAlreadyExists(
                'The users like for this post already exists'
            );
        }
    }
    public function delete(UUID $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM likes WHERE id = :id');
        $statement->execute([
            ':id' => $id
        ]);
    }
}