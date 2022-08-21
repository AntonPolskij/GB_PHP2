<?php

namespace GeekBrains\LevelTwo\tests\Repositories;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;

class SqlitePostsRepositoryTest extends TestCase
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
            ':heading' => 'Ivan asdasdasdas',
            ':text' => 'Nikitin weqweqweq',
        ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqlitePostsRepository($connectionStub);

        $repository->save( new Post 
        (new UUID ('123e4567-e89b-12d3-a456-426614174000'),new UUID ('123e4567-e89b-12d3-a456-426614174001'), 'Ivan asdasdasdas', 'Nikitin weqweqweq')         
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
            'heading' => 'Ivan asdasdasdas',
            'text' => 'Nikitin weqweqweq',
        ]);
        

        $repository = new SqlitePostsRepository($connectionStub);

        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

    public function testItThrowAnExceptionWhenPostNotFound(): void
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementMock = $this->createStub(PDOStatement::class);

        $statementMock->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqlitePostsRepository($connectionStub);

        $this->expectException(PostNotFoundException::class);

        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }
}