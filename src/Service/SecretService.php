<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\SecretInput;
use App\Entity\Secret;
use App\Entity\User;
use App\Enum\SecurityEventType;
use App\Repository\SecretRepositoryInterface;
use App\Security\Encryption\EncryptionServiceInterface;
use DateTimeImmutable;
use RuntimeException;

final class SecretService
{
    public function __construct(
        private readonly SecretRepositoryInterface $secretRepository,
        private readonly EncryptionServiceInterface $encryptionService,
        private readonly SecurityLogger $securityLogger,
    ) {
    }

    public function createSecret(User $user, SecretInput $input, string $ipAddress, string $userAgent): Secret
    {
        $secret = new Secret(
            $this->secretRepository->nextId(),
            $user,
            $input->name,
            $this->encryptionService->encrypt($input->username),
            $this->encryptionService->encrypt($input->password),
            $this->encryptionService->encrypt($input->url),
            $this->encryptionService->encrypt($input->notes),
            new DateTimeImmutable(),
            new DateTimeImmutable(),
        );

        $this->secretRepository->save($secret);
        $this->securityLogger->log($user->getId(), SecurityEventType::SECRET_CREATED, $ipAddress, $userAgent);

        return $secret;
    }

    public function viewSecret(User $user, int $secretId): array
    {
        $secret = $this->requireOwnedSecret($user, $secretId);

        return [
            'id' => $secret->getId(),
            'name' => $secret->getName(),
            'username' => $this->encryptionService->decrypt($secret->getUsernameEncrypted()),
            'password' => $this->encryptionService->decrypt($secret->getPasswordEncrypted()),
            'url' => $this->encryptionService->decrypt($secret->getUrlEncrypted()),
            'notes' => $this->encryptionService->decrypt($secret->getNotesEncrypted()),
        ];
    }

    public function updateSecret(User $user, int $secretId, SecretInput $input, string $ipAddress, string $userAgent): void
    {
        $secret = $this->requireOwnedSecret($user, $secretId);
        $secret->updateEncryptedFields(
            $input->name,
            $this->encryptionService->encrypt($input->username),
            $this->encryptionService->encrypt($input->password),
            $this->encryptionService->encrypt($input->url),
            $this->encryptionService->encrypt($input->notes),
        );

        $this->secretRepository->save($secret);
        $this->securityLogger->log($user->getId(), SecurityEventType::SECRET_UPDATED, $ipAddress, $userAgent);
    }

    public function deleteSecret(User $user, int $secretId, string $ipAddress, string $userAgent): void
    {
        $secret = $this->requireOwnedSecret($user, $secretId);
        $this->secretRepository->remove($secret);
        $this->securityLogger->log($user->getId(), SecurityEventType::SECRET_DELETED, $ipAddress, $userAgent);
    }

    private function requireOwnedSecret(User $user, int $secretId): Secret
    {
        $secret = $this->secretRepository->find($secretId);

        if ($secret === null || $secret->getUser()->getId() !== $user->getId()) {
            throw new RuntimeException('Secret not found.');
        }

        return $secret;
    }
}
