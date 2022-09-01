<?php

namespace GeekBrains\LevelTwo\Blog\Commands\FakeData;

use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Symfony\Component\Console\Input\InputOption;

class PopulateDB extends Command
{
    // Внедряем генератор тестовых данных и
    // репозитории пользователей и статей
    public function __construct(
        private \Faker\Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
        private CommentsRepositoryInterface $commentsRepository,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption(
                'users-number',
                'u',
                InputOption::VALUE_REQUIRED,
                'How many users to create',
            )
            ->addOption(
                'posts-number',
                'p',
                InputOption::VALUE_REQUIRED,
                'How many posts to create',
            );
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        // Создаём десять пользователей
        $users = [];
        $posts = [];
        $u = $input->getOption('users-number');
        for ($i = 0; $i < $u; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getUsername());
        }
        // От имени каждого пользователя
        // создаём по двадцать статей
        foreach ($users as $user) {
            $p = $input->getOption('posts-number');
            for ($i = 0; $i < $p; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created: ' . $post->getHeading());
            }
        }
        foreach ($posts as $post) {
            $comment = $this->createFakeComment($user, $post);
            $output->writeln('Comment created:' . $comment->getText());
        }
        return Command::SUCCESS;
    }




    private function createFakeUser(): User
    {
        $user = User::createFrom(
            // Генерируем имя пользователя
            $this->faker->userName(),
            $this->faker->firstName(),
            $this->faker->lastName(),
            // Генерируем пароль
            $this->faker->password,
        );
        // Сохраняем пользователя в репозиторий
        $this->usersRepository->save($user);
        return $user;
    }
    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author,
            // Генерируем предложение не длиннее шести слов
            $this->faker->sentence(6, true),
            // Генерируем текст
            $this->faker->realText
        );
        // Сохраняем статью в репозиторий
        $this->postsRepository->save($post);
        return $post;
    }
    private function createFakeComment(User $author, Post $post): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $author,
            // Генерируем предложение не длиннее шести слов
            $post,
            // Генерируем текст
            $this->faker->realText
        );
        // Сохраняем статью в репозиторий
        $this->commentsRepository->save($comment);
        return $comment;
    }
}
