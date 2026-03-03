<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OllamaModelStatus;
use Illuminate\Database\Eloquent\Model;

class OllamaModel extends Model
{
    protected $fillable = [
        'model_name',
        'display_name',
        'size_gb',
        'ram_required_gb',
        'description',
        'tags',
        'status',
        'progress',
        'pulled_at',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'status' => OllamaModelStatus::class,
            'progress' => 'integer',
            'pulled_at' => 'datetime',
        ];
    }
}
