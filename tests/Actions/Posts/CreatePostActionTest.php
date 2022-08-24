<?php

namespace GeekBrains\LevelTwo\tests\Actions\Posts;

use PHPUnit\Framework\TestCase;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Http\Request;
use GeekBrains\LevelTwo\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Http\Actions\Posts\CreatePost;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\tests\DummyLogger;

class CreatePostActionTest extends TestCase
{
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request([], [], '{
    "user_id": "0ae45b27-b306-4271-a85d-02247adb3ee9",
    "heading": "some text",
    "text": "some title"
}');


        $usersRepository = $this->usersRepository([new User(new UUID('0ae45b27-b306-4271-a85d-02247adb3ee9'), 'anton', 'Anton', 'Polskiy')]);

        $postsRepository = $this->postsRepository([]);



        $action = new CreatePost($usersRepository, $postsRepository, new DummyLogger);
        $response = $action->handle($request);
        if (!empty($postsRepository)) {
            $this->assertInstanceOf(SuccessfulResponse::class, $response);
            $response->send();
        }
    }


    private function postsRepository(array $posts): PostsRepositoryInterface
    {
        return new class($posts) implements PostsRepositoryInterface
        {
            public function __construct(
                private array $posts
            ) {
            }
            public function save(Post $post): void
            {
                $statement = $this->connection->prepare(
                    'INSERT INTO posts (id, user_id, heading, text) VALUES (:id, :user_id, :heading, :text)'
                );

                $statement->execute([
                    ':id' => (string)$post->getId(),
                    ':user_id' => (string)$post->getUser_id(),
                    ':heading' => $post->getHeading(),
                    ':text' => $post->getText()
                ]);
            }

            public function get(UUID $id): Post
            {
                throw new PostNotFoundException("Not found");
            }

            public function delete(UUID $id): void
            {
            }
        };
    }

    private function usersRepository(array $users): UsersRepositoryInterface
    {
        // В конструктор анонимного класса передаём массив пользователей
        return new class($users) implements UsersRepositoryInterface
        {
            public function __construct(
                private array $users
            ) {
            }
            public function save(User $user): void
            {
            }
            public function getById(UUID $id): User
            {
                throw new UserNotFoundException("Not found");
            }
            public function getByUsername(string $username): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $username === $user->getUsername()) {
                        return $user;
                    }
                }
                throw new UserNotFoundException("Not found");
            }
        };
    }
}
