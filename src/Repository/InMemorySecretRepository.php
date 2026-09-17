<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Secret;

final class InMemorySecretRepository implements SecretRepositoryInterface
{
    /** @var array<int, Secret> */
    private array $secrets = [];

    private int $nextId = 1;

    public function nextId(): int
    {
        return $this->nextId++;
    }

    public function save(Secret $secret): void
    {
        $this->secrets[$secret->getId()] = $secret;
    }

    public function find(int $id): ?Secret
    {
        return $this->secrets[$id] ?? null;
    }

    public function remove(Secret $secret): void
    {
        unset($this->secrets[$secret->getId()]);
    }
}
