<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog\UUID;

class Post
{

    public function __construct(
        private UUID   $id,
        private User   $user,
        private string $heading,
        private string $text,
    ) {
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
        return $this->user->getId();
    }

    /**
     * Get the value of text
     */ 
    public function getText(): string
    {
        return $this->text;
    }
}