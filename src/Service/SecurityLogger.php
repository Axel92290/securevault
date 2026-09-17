<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\SecurityEventType;

final class SecurityLogger
{
    /** @var list<array{userId:int,eventType:SecurityEventType,ipAddress:string,userAgent:string,metadata:array<string,mixed>}> */
    private array $events = [];

    /** @param array<string,mixed> $metadata */
    public function log(int $userId, SecurityEventType $eventType, string $ipAddress, string $userAgent, array $metadata = []): void
    {
        $this->events[] = [
            'userId' => $userId,
            'eventType' => $eventType,
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
            'metadata' => $metadata,
        ];
    }

    /** @return list<array{userId:int,eventType:SecurityEventType,ipAddress:string,userAgent:string,metadata:array<string,mixed>}> */
    public function all(): array
    {
        return $this->events;
    }
}
