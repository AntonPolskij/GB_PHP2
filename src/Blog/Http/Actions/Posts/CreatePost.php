<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\Posts;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Blog\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\HttpException;

use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;

class CreatePost implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $userId = new UUID($request->jsonBodyField('user_id'));
            $user = $this->usersRepository->getById($userId);

            $newPostId = UUID::random();

            $post = new Post(
                $newPostId,
                $user,
                $request->jsonBodyField('heading'),
                $request->jsonBodyField('text')
            );
            $this->postRepository->save($post);
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => (string)$newPostId,
        ]);
    }
}
