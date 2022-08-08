<?php

namespace GeekBrains\LevelTwo\Blog;

class Post
{
    private int $id;
    private int $user_id;
    private string $heading;
    private string $text;

    public function __construct(int $id, User $user, string $heading, string $text)
    {
        $this->id = $id;
        $this->user_id = $user->getId();
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
    public function getId(): int
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
    public function getUser_id(): int
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