<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\DTO\SecretInput;
use App\Entity\User;
use App\Repository\InMemorySecretRepository;
use App\Security\Encryption\LibsodiumEncryptionService;
use App\Service\SecretService;
use App\Service\SecurityLogger;
use PHPUnit\Framework\TestCase;

final class SecretPersistenceEncryptionTest extends TestCase
{
    public function testStoredSecretDoesNotContainPlaintextInPersistenceModel(): void
    {
        $service = new SecretService(
            new InMemorySecretRepository(),
            new LibsodiumEncryptionService(random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES)),
            new SecurityLogger(),
        );

        $user = new User(1, 'owner@example.com', 'hashed-password');
        $secret = $service->createSecret(
            $user,
            new SecretInput('GitHub', 'octocat', 'my-plaintext-password', 'https://github.com', 'my-private-note'),
            '127.0.0.1',
            'phpunit'
        );

        self::assertStringNotContainsString('octocat', $secret->getUsernameEncrypted());
        self::assertStringNotContainsString('my-plaintext-password', $secret->getPasswordEncrypted());
        self::assertStringNotContainsString('https://github.com', $secret->getUrlEncrypted());
        self::assertStringNotContainsString('my-private-note', $secret->getNotesEncrypted());
    }
}
