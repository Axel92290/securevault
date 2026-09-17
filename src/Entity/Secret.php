<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;

final class Secret
{
    public function __construct(
        private readonly int $id,
        private readonly User $user,
        private string $name,
        private string $usernameEncrypted,
        private string $passwordEncrypted,
        private string $urlEncrypted,
        private string $notesEncrypted,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsernameEncrypted(): string
    {
        return $this->usernameEncrypted;
    }

    public function getPasswordEncrypted(): string
    {
        return $this->passwordEncrypted;
    }

    public function getUrlEncrypted(): string
    {
        return $this->urlEncrypted;
    }

    public function getNotesEncrypted(): string
    {
        return $this->notesEncrypted;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateEncryptedFields(string $name, string $usernameEncrypted, string $passwordEncrypted, string $urlEncrypted, string $notesEncrypted): void
    {
        $this->name = $name;
        $this->usernameEncrypted = $usernameEncrypted;
        $this->passwordEncrypted = $passwordEncrypted;
        $this->urlEncrypted = $urlEncrypted;
        $this->notesEncrypted = $notesEncrypted;
        $this->updatedAt = new DateTimeImmutable();
    }
}
