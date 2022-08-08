<?php

namespace GeekBrains\LevelTwo\Blog;

class Comment
{
    private int $id;
    private int $user_id;
    private int $post_id;
    private string $text;

    public function __construct(int $id, User $user, Post $post, string $text)
    {
        $this->id = $id;
        $this->user_id = $user->getId();
        $this->post_id = $post->getId();
        $this->text = $text;
    }


    public function __toString(): string
    {
        return $this->getText();
    }
    /**
     * Get the value of id
     */ 
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id(): int
    {
        return $this->user_id;
    }

    /**
     * Get the value of post_id
     */ 
    public function getPost_id(): int
    {
        return $this->post_id;
    }

    /**
     * Get the value of text
     */ 
    public function getText(): string
    {
        return $this->text;
    }
}