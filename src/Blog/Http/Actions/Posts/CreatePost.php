<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\Posts;

use Psr\Log\LoggerInterface;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Blog\Http\Response;
use GeekBrains\LevelTwo\Blog\Http\ErrorResponse;
use GeekBrains\LevelTwo\Exceptions\HttpException;

use GeekBrains\LevelTwo\Blog\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Blog\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Blog\Http\Auth\AuthenticationInterface;
use GeekBrains\LevelTwo\Blog\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Blog\Http\Auth\TokenAuthenticationInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

class CreatePost implements ActionInterface
{
    public function __construct(
        // private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postRepository,
        private TokenAuthenticationInterface $authentication,
        private LoggerInterface $logger
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            // $userId = new UUID($request->jsonBodyField('user_id'));
            // $user = $this->usersRepository->getById($userId);

            $user = $this->authentication->user($request);

            $newPostId = UUID::random();

            $post = new Post(
                $newPostId,
                $user,
                $request->jsonBodyField('heading'),
                $request->jsonBodyField('text')
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->postRepository->save($post);
        
        $this->logger->info("Post created: $newPostId");

        return new SuccessfulResponse([
            'id' => (string)$newPostId,
        ]);
    }
}
