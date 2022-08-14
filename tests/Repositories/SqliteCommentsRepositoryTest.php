<?php

namespace GeekBrains\LevelTwo\test\Repositories;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;

class SqliteCommentsRepositoryTest extends TestCase
{
    public function testItSavesDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':id' => '123e4567-e89b-12d3-a456-426614174000',
                ':user_id' => '123e4567-e89b-12d3-a456-426614174001',
                ':post_id' => '123e4567-e89b-12d3-a456-426614174002',
                ':text' => 'Nikitin weqweqweq',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteCommentsRepository($connectionStub);

        $repository->save(
            new Comment(new UUID('123e4567-e89b-12d3-a456-426614174000'), new UUID('123e4567-e89b-12d3-a456-426614174001'), new UUID('123e4567-e89b-12d3-a456-426614174002'), 'Nikitin weqweqweq')
        );
    }

    public function testItGetPostById(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createStub(PDOStatement::class);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':id' => '123e4567-e89b-12d3-a456-426614174000',
            ]);


        $statementMock->method('fetch')->willReturn([
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'user_id' => '123e4567-e89b-12d3-a456-426614174001',
            'post_id' => '123e4567-e89b-12d3-a456-426614174002',
            'text' => 'Nikitin weqweqweq',
        ]);


        $repository = new SqliteCommentsRepository($connectionStub);

        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

    public function testItThrowAnExceptionWhenPostNotFound(): void
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementMock = $this->createStub(PDOStatement::class);

        $statementMock->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteCommentsRepository($connectionStub);

        $this->expectException(CommentNotFoundException::class);

        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }
}
