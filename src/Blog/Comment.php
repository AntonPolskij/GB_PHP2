<?php

namespace GeekBrains\LevelTwo\Blog;

class Comment
{

    public function __construct(
        private UUID $id,
        private User $user,
        private Post $post,
        private string $text,
    )
    {
    }


    public function __toString(): string
    {
        return $this->getText();
    }
    /**
     * Get the value of id
     */ 
    public function getId(): UUID
    {
        return $this->id;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id(): UUID
    {
        return $this->user->getId();
    }

    /**
     * Get the value of post_id
     */ 
    public function getPost_id(): UUID
    {
        return $this->post->getId();
    }

    /**
     * Get the value of text
     */ 
    public function getText(): string
    {
        return $this->text;
    }
}