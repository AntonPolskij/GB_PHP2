<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog\UUID;

class Post
{
    private UUID $id;
    private UUID $user_id;
    private string $heading;
    private string $text;

    public function __construct(UUID $id, UUID $user_id, string $heading, string $text)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->heading = $heading;
        $this->text = $text;
    }

    public function __toString(): string
    {
        return $this->getHeading() . PHP_EOL . $this->getText();
    }



    /**
     * Get the value of id
     */ 
    public function getId(): UUID
    {
        return $this->id;
    }

    /**
     * Get the value of heading
     */ 
    public function getHeading(): string
    {
        return $this->heading;
    }

    /**
     * Get the value of user_id
     */ 
    public function getUser_id(): UUID
    {
        return $this->user_id;
    }

    /**
     * Get the value of text
     */ 
    public function getText(): string
    {
        return $this->text;
    }
}