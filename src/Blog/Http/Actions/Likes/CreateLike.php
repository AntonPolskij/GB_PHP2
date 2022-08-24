<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\Likes;

use Error;
use Exception;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class CreateLike implements ActionInterface
{
    public function __construct(
        private LikesRepositoryInterface $likesRepo,
        private UsersRepositoryInterface $usersRepo,
        private PostsRepositoryInterface $postsRepo,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $postId = $request->jsonBodyField('post_id');
            $userId = $request->jsonBodyField('user_id');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $this->likesRepo->checkUserLikeForPostExists($postId, $userId);
        } catch (Exception $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $newLikeId = UUID::random();
            $post = $this->postsRepo->get(new UUID($postId));
            $user = $this->usersRepo->getById(new UUID($userId));
        } catch (Exception $e) {
            return new ErrorResponse($e->getMessage());
        }

        $like = new Like(
            $newLikeId,
            $user,
            $post
        );

        $this->likesRepo->save($like);

        return new SuccessfulResponse([
            'id' => (string)$newLikeId
        ]);
    }
}
