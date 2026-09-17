<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Secret;

interface SecretRepositoryInterface
{
    public function nextId(): int;

    public function save(Secret $secret): void;

    public function find(int $id): ?Secret;

    public function remove(Secret $secret): void;
}
