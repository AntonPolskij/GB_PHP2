<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepository;

use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\UUID;

interface LikesRepositoryInterface
{
    public function save(Like $comment): void;

    public function getByPostId(UUID $id): array;

    public function delete(UUID $id): void;

    public function checkUserLikeForPostExists($postId, $userId): void;
}
