<?php

declare(strict_types=1);

namespace App\Security\Encryption;

use RuntimeException;

final class LibsodiumEncryptionService implements EncryptionServiceInterface
{
    private const NONCE_BYTES = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES;
    private const KEY_BYTES = SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_KEYBYTES;

    public function __construct(private readonly string $masterKey)
    {
        if (strlen($masterKey) !== self::KEY_BYTES) {
            throw new RuntimeException('Invalid encryption key length.');
        }
    }

    public static function fromBase64Key(string $encodedKey): self
    {
        $key = sodium_base642bin($encodedKey, SODIUM_BASE64_VARIANT_ORIGINAL);

        return new self($key);
    }

    public function encrypt(string $plaintext): string
    {
        $nonce = random_bytes(self::NONCE_BYTES);

        // A unique nonce is generated for each operation to preserve AEAD security guarantees.
        $ciphertext = sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($plaintext, '', $nonce, $this->masterKey);

        return sodium_bin2base64($nonce . $ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);
    }

    public function decrypt(string $ciphertext): string
    {
        $payload = sodium_base642bin($ciphertext, SODIUM_BASE64_VARIANT_ORIGINAL);

        if (strlen($payload) <= self::NONCE_BYTES) {
            throw new RuntimeException('Invalid ciphertext payload.');
        }

        $nonce = substr($payload, 0, self::NONCE_BYTES);
        $encrypted = substr($payload, self::NONCE_BYTES);
        $plaintext = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt($encrypted, '', $nonce, $this->masterKey);

        if ($plaintext === false) {
            throw new RuntimeException('Ciphertext authentication failed.');
        }

        return $plaintext;
    }
}
