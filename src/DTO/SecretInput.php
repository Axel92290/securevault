<?php

declare(strict_types=1);

namespace App\DTO;

use InvalidArgumentException;

final class SecretInput
{
    public function __construct(
        public readonly string $name,
        public readonly string $username,
        public readonly string $password,
        public readonly string $url,
        public readonly string $notes,
    ) {
        if ($name === '' || mb_strlen($name) > 255) {
            throw new InvalidArgumentException('Invalid secret name.');
        }

        if (mb_strlen($username) > 255 || mb_strlen($password) > 1024 || mb_strlen($url) > 2048 || mb_strlen($notes) > 8192) {
            throw new InvalidArgumentException('Secret input too long.');
        }
    }
}
