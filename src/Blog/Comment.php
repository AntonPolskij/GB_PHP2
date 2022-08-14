<?php

namespace GeekBrains\LevelTwo\Blog;

class Comment
{
    private UUID $id;
    private UUID $user_id;
    private UUID $post_id;
    private string $text;

    public function __construct(UUID $id, UUID $user_id, UUID $post_id, string $text)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->post_id = $post_id;
        $this->text = $text;
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
        return $this->user_id;
    }

    /**
     * Get the value of post_id
     */ 
    public function getPost_id(): UUID
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