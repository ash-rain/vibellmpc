<?php

declare(strict_types=1);

namespace VibellmPC\Common\Contracts;

use VibellmPC\Common\DTOs\DeviceInfo;
use VibellmPC\Common\DTOs\DeviceStatusResult;
use VibellmPC\Common\DTOs\PairingResult;

interface DeviceRegistryContract
{
    public function getDeviceInfo(): DeviceInfo;

    public function getDeviceStatus(string $deviceId): DeviceStatusResult;

    public function claimDevice(string $deviceId, int $userId): PairingResult;

    public function registerDevice(DeviceInfo $deviceInfo): void;
}
