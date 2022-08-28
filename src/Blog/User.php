<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog\UUID;

class User
{
        public function __construct(
                private UUID $id,
                private string $username,
                private string $name,
                private string $surname,
                private string $hashedPassword,
        ) {
        }

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

        /**
         * Get the value of password
         */
        public function hashedPassword()
        {
                return $this->hashedPassword;
        }
        private static function hash(string $password, UUID $id): string
        {
                return hash('sha256', $password . $id);
        }
        public function checkPassword(string $password): bool
        {
                return $this->hashedPassword === self::hash($password, $this->id);
        }
        public static function createFrom(
                string $username,
                $name,
                $surname,
                $password,
        ): self {
                $id = UUID::random();
                return new self(
                        $id,
                        $username,
                        $name,
                        $surname,
                        self::hash($password, $id),
                );
        }
}
