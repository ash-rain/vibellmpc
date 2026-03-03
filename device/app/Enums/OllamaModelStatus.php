<?php

declare(strict_types=1);

namespace App\Enums;

enum OllamaModelStatus: string
{
    case Available = 'available';
    case Downloading = 'downloading';
    case Installed = 'installed';
    case Error = 'error';

    public function label(): string
    {
        return match($this) {
            self::Available => 'Available',
            self::Downloading => 'Downloading',
            self::Installed => 'Installed',
            self::Error => 'Error',
        };
    }
}
