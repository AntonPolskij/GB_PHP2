<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;

class Like
{
    public function __construct(
        private UUID $id,
        private User $user,
        private Post $post
    )
    {
        
    }
    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }
    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    /**
     * @return UUID
     */
    public function getId(): UUID
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->getId();
    }
    
}