<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Security\Encryption\LibsodiumEncryptionService;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class EncryptionServiceTest extends TestCase
{
    public function testEncryptAndDecryptRoundTrip(): void
    {
        $service = new LibsodiumEncryptionService(random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES));
        $plaintext = 'super-secret-value';

        $ciphertext = $service->encrypt($plaintext);

        self::assertNotSame($plaintext, $ciphertext);
        self::assertSame($plaintext, $service->decrypt($ciphertext));
    }

    public function testDecryptFailsOnTamperedCiphertext(): void
    {
        $service = new LibsodiumEncryptionService(random_bytes(SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES));
        $ciphertext = $service->encrypt('secret');
        $payload = sodium_base642bin($ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);
        $tampered = $payload;
        $tampered[strlen($tampered) - 1] = $tampered[strlen($tampered) - 1] ^ chr(1);

        $this->expectException(RuntimeException::class);

        $service->decrypt(sodium_bin2base64($tampered, SODIUM_BASE64_VARIANT_ORIGINAL));
    }
}
