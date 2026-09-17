# 🔐 SecureVault

SecureVault is a secure web application for storing and managing sensitive information in an encrypted digital vault.

Built with **PHP 8.3+, Symfony 7, PostgreSQL and Docker**, the project follows a **Security by Design** approach and aims to demonstrate both backend development and application security practices.

> ⚠️ SecureVault is currently under development and is primarily intended as an educational and portfolio project.

---

## 🛡️ Security by Design

Security is treated as a core requirement of SecureVault rather than an additional feature.

Sensitive information stored by users must never be persisted in plaintext.

The application is designed around several security principles:

* encrypted sensitive data at rest;
* secure password hashing;
* Multi-Factor Authentication;
* authentication rate limiting;
* brute-force protection;
* CSRF protection;
* strict access control;
* session management and revocation;
* security event logging;
* secure secret management;
* protection against unauthorized object access;
* automated security tests.

---

## ✨ Main Features

### 🔑 Authentication

* User registration
* Secure login
* Logout
* Symfony password hashing
* CSRF protection
* Authentication rate limiting
* Failed login monitoring

### 🔐 Encrypted Vault

Users can securely manage sensitive information such as:

* credentials;
* passwords;
* API keys;
* private notes;
* URLs;
* other sensitive data.

Sensitive values are encrypted before being stored in PostgreSQL.

Example:

```text
User input
    ↓
Validation
    ↓
Encryption Service
    ↓
Encrypted value
    ↓
PostgreSQL
```

Plaintext secrets must never be stored directly in the database.

---

## 🔒 Encryption

SecureVault uses authenticated encryption to protect sensitive information.

Encryption responsibilities are centralized in a dedicated service.

```text
Controller
    ↓
Secret Service
    ↓
Encryption Service
    ↓
Database
```

The encryption key must remain outside the database and must never be committed to the Git repository.

The planned implementation relies on PHP's **libsodium** cryptographic library.

---

## 📱 Multi-Factor Authentication

SecureVault supports Time-based One-Time Password authentication using **TOTP**.

It can be used with applications such as:

* Google Authenticator;
* Microsoft Authenticator;
* Authy;
* Bitwarden;
* 1Password.

Authentication flow:

```text
Email + password
        ↓
Credentials valid
        ↓
MFA enabled?
     /       \
   No         Yes
   ↓           ↓
Access       TOTP verification
               ↓
             Access
```

---

## 🚨 Security Monitoring

SecureVault records security-related events to provide an audit trail.

Examples include:

```text
LOGIN_SUCCESS
LOGIN_FAILURE
MFA_ENABLED
MFA_DISABLED
MFA_FAILURE
PASSWORD_CHANGED
SECRET_CREATED
SECRET_UPDATED
SECRET_DELETED
SESSION_REVOKED
SUSPICIOUS_LOGIN
```

Security logs must never contain sensitive information such as:

* passwords;
* encrypted vault content in plaintext;
* encryption keys;
* MFA tokens;
* session tokens.

---

## 💻 Session Management

Users can inspect and revoke active sessions.

Planned features include:

* current session identification;
* active device list;
* last activity;
* IP information;
* user agent information;
* individual session revocation;
* revocation of all other sessions.

---

## 🛑 Brute-Force Protection

SecureVault monitors repeated authentication failures.

The application uses Symfony's rate limiting capabilities to reduce the risk of automated authentication attacks.

Example:

```text
Repeated login failures
        ↓
Rate Limiter
        ↓
Temporary restriction
        ↓
Security Event
```

---

## 🌐 REST API

SecureVault also exposes a REST API.

Planned endpoints include:

```http
GET /api/secrets
GET /api/secrets/{id}
POST /api/secrets
PUT /api/secrets/{id}
DELETE /api/secrets/{id}
```

API resources are protected by the same authorization rules as the web interface.

---

## 🧱 Tech Stack

### Backend

* PHP 8.3+
* Symfony 7
* Doctrine ORM
* Symfony Security
* Symfony Validator
* Symfony Rate Limiter

### Database

* PostgreSQL

### Frontend

* Twig
* Bootstrap 5
* JavaScript

### Security

* Symfony Security
* libsodium
* MFA / TOTP
* CSRF protection
* Rate Limiting
* Access Control
* Security Event Logging

### Infrastructure

* Docker
* Docker Compose

### Testing

* PHPUnit

### API

* REST
* JSON

---

## 📂 Project Structure

```text
src/
├── Controller/
│   ├── Api/
│   └── Auth/
├── DTO/
├── Entity/
├── Enum/
├── EventSubscriber/
├── Repository/
├── Security/
│   ├── Authentication/
│   ├── Encryption/
│   └── Voter/
└── Service/

tests/
├── Functional/
├── Integration/
├── Security/
└── Unit/
```

---

## 📋 Requirements

Before installing SecureVault, make sure you have:

* Docker;
* Docker Compose;
* Git.

For development without Docker:

* PHP 8.3 or higher;
* Composer;
* PostgreSQL;
* Symfony CLI.

---

## 🚀 Installation

Clone the repository:

```bash
git clone https://github.com/Axel92290/securevault.git
```

Enter the project:

```bash
cd securevault
```

Create the environment configuration:

```bash
cp .env.example .env
```

Start the containers:

```bash
docker compose up -d
```

Install PHP dependencies:

```bash
composer install
```

Create the database:

```bash
php bin/console doctrine:database:create
```

Run database migrations:

```bash
php bin/console doctrine:migrations:migrate
```

Start the application if necessary:

```bash
symfony server:start
```

---

## 🧪 Tests

Run all tests with:

```bash
php bin/phpunit
```

Security-specific tests are located in:

```text
tests/Security/
```

Examples include:

* access control tests;
* authentication tests;
* encryption tests;
* brute-force protection tests;
* authorization tests.

One of the project's security requirements is to ensure that a secret submitted by a user can never be found in plaintext in the database.

---

## 🔑 Environment Variables

Sensitive configuration must be stored using environment variables.

Example:

```dotenv
DATABASE_URL=
APP_SECRET=
VAULT_ENCRYPTION_KEY=
```

Never commit production credentials or encryption keys.

The `.env.example` file contains only configuration examples and must not contain real secrets.

---

## ⚠️ Security Notice

SecureVault is currently an educational and portfolio project.

It should **not be used to store production credentials or critical personal information** unless a complete independent security review has been performed.

Cryptographic implementations should always rely on established and audited libraries rather than custom cryptographic algorithms.

---

## 🗺️ Roadmap

### Version 0.1

* [ ] Docker environment
* [ ] PostgreSQL database
* [ ] User entity
* [ ] Registration
* [ ] Authentication
* [ ] Login rate limiting

### Version 0.2

* [ ] Secret entity
* [ ] Secret CRUD
* [ ] Encryption service
* [ ] Secure encryption key management
* [ ] Secret access control

### Version 0.3

* [ ] TOTP MFA
* [ ] MFA recovery system
* [ ] Security event logging

### Version 0.4

* [ ] Active session management
* [ ] Session revocation
* [ ] Suspicious authentication detection
* [ ] Security dashboard

### Version 0.5

* [ ] REST API
* [ ] API authentication
* [ ] API rate limiting

### Version 1.0

* [ ] Full automated test suite
* [ ] Security review
* [ ] Documentation
* [ ] CI/CD pipeline
* [ ] Production-ready Docker configuration

---

## 🎯 Project Goals

SecureVault was created to strengthen and demonstrate skills in:

* PHP;
* Symfony;
* backend architecture;
* REST APIs;
* PostgreSQL;
* Docker;
* automated testing;
* authentication;
* authorization;
* cryptography fundamentals;
* secure application development;
* cybersecurity;
* Security by Design.

---

## 👤 Author

**Axel Chasseloup**

GitHub: [@Axel92290](https://github.com/Axel92290)

---

## 📄 License

This project is distributed under the MIT License.
