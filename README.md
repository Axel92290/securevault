# securevault

SecureVault is a security-focused vault backend prototype aligned with Symfony-style architecture and Security by Design principles.

## Implemented security foundation

- Encryption abstraction (`EncryptionServiceInterface`) with libsodium AEAD implementation (`LibsodiumEncryptionService`)
- Secret domain model with encrypted-at-rest fields (`usernameEncrypted`, `passwordEncrypted`, `urlEncrypted`, `notesEncrypted`)
- Ownership enforcement in `SecretService` (cross-user access blocked)
- Security event type enum and centralized `SecurityLogger`
- Input DTO validation for secret payloads (`SecretInput`)

## Test coverage included

- `EncryptionServiceTest`: authenticated encryption and tamper detection
- `SecretPersistenceEncryptionTest`: verifies plaintext values are not persisted in the stored secret model
- `AccessControlTest`: verifies User A cannot read, update, or delete User B secrets

## Run tests

```bash
phpunit --configuration phpunit.xml
```
