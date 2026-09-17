<?php

declare(strict_types=1);

namespace App\Security\Encryption;

interface EncryptionServiceInterface
{
    public function encrypt(string $plaintext): string;

    public function decrypt(string $ciphertext): string;
}
