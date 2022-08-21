<?php

namespace GeekBrains\LevelTwo\Blog\Http\Actions\Comments;

use GeekBrains\LevelTwo\Blog\Comment;
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
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;

class CreateComment implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postRepository,
        private CommentsRepositoryInterface $commentRepository,
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $postId = new UUID($request->jsonBodyField('post_id'));
            $post = $this->postRepository->get($postId);

            $userId = new UUID($request->jsonBodyField('user_id'));
            $user = $this->usersRepository->getById($userId);


            $commentId = UUID::random();

            $comment = new Comment(
                $commentId,
                $user,
                $post,
                $request->jsonBodyField('text')
            );
            $this->commentRepository->save($comment);
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
            'id' => (string)$commentId,
        ]);
    }
}
