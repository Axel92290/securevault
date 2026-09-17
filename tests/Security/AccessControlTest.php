<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\DTO\SecretInput;
use App\Entity\User;
use App\Repository\InMemorySecretRepository;
use App\Security\Encryption\LibsodiumEncryptionService;
use App\Service\SecretService;
use App\Service\SecurityLogger;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class AccessControlTest extends TestCase
{
    public function testUserCannotReadModifyOrDeleteAnotherUsersSecret(): void
    {
        $service = $this->buildSecretService();

        $userA = new User(1, 'a@example.com', 'hashed-password-a');
        $userB = new User(2, 'b@example.com', 'hashed-password-b');

        $secret = $service->createSecret(
            $userA,
            new SecretInput('Email', 'alice', 'alice-password', 'https://mail.example.com', 'private note'),
            '127.0.0.1',
            'phpunit'
        );

        $this->expectException(RuntimeException::class);
        $service->viewSecret($userB, $secret->getId());
    }

    public function testUserCannotModifyOrDeleteAnotherUsersSecret(): void
    {
        $service = $this->buildSecretService();

        $userA = new User(1, 'a@example.com', 'hashed-password-a');
        $userB = new User(2, 'b@example.com', 'hashed-password-b');

        $secret = $service->createSecret(
            $userA,
            new SecretInput('Email', 'alice', 'alice-password', 'https://mail.example.com', 'private note'),
            '127.0.0.1',
            'phpunit'
        );

        try {
            $service->updateSecret(
                $userB,
                $secret->getId(),
                new SecretInput('Updated', 'eve', 'bad', 'https://evil.example.com', 'bad'),
                '127.0.0.1',
                'phpunit'
            );
            self::fail('Expected update to be denied.');
        } catch (RuntimeException) {
            self::assertTrue(true);
        }

        $this->expectException(RuntimeException::class);
        $service->deleteSecret($userB, $secret->getId(), '127.0.0.1', 'phpunit');
    }

    private function buildSecretService(): SecretService
    {
        return new SecretService(
            new InMemorySecretRepository(),
            new LibsodiumEncryptionService(random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES)),
            new SecurityLogger(),
        );
    }
}
