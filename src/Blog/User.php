<?php

namespace GeekBrains\LevelTwo\Blog;

class User
{
    public function __construct(
        private UUID $id,
        private string $username,
        private string $name,
        private string $surname,
    )
    {}

    public function __toString(): string
    {
        return $this->getName() . " " . $this->getSurname();
    }

        /**
         * Get the value of name
         */ 
        public function getName(): string
        {
                return $this->name;
        }

        /**
         * Get the value of surname
         */ 
        public function getSurname(): string
        {
                return $this->surname;
        }

        /**
         * Get the value of id
         */ 
        public function getId(): UUID
        {
                return $this->id;
        }

        /**
         * Get the value of username
         */ 
        public function getUsername()
        {
                return $this->username;
        }
}