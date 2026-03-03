<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Ollama Model Catalogue
    |--------------------------------------------------------------------------
    |
    | These models are presented to the user during the setup wizard's
    | model-selection step. The 'name' field is the Ollama tag used when
    | pulling. Set 'recommended' => true on the model shown with a badge.
    |
    */

    'catalogue' => [
        [
            'name' => 'llama3.2:3b',
            'display_name' => 'Llama 3.2 3B',
            'size_gb' => 2.0,
            'ram_required_gb' => 4,
            'description' => 'Fast, everyday chat. Best for quick responses.',
            'tags' => ['chat', 'fast'],
            'recommended' => false,
        ],
        [
            'name' => 'llama3.2:8b',
            'display_name' => 'Llama 3.2 8B',
            'size_gb' => 4.7,
            'ram_required_gb' => 8,
            'description' => 'Balanced performance. Recommended for most users.',
            'tags' => ['chat', 'balanced'],
            'recommended' => true,
        ],
        [
            'name' => 'mistral:7b',
            'display_name' => 'Mistral 7B',
            'size_gb' => 4.1,
            'ram_required_gb' => 8,
            'description' => 'Great for coding and structured reasoning.',
            'tags' => ['coding', 'reasoning'],
            'recommended' => false,
        ],
        [
            'name' => 'phi4-mini',
            'display_name' => 'Phi-4 Mini',
            'size_gb' => 2.5,
            'ram_required_gb' => 6,
            'description' => 'Microsoft compact model. Fast and capable.',
            'tags' => ['chat', 'compact'],
            'recommended' => false,
        ],
        [
            'name' => 'gemma3:4b',
            'display_name' => 'Gemma 3 4B',
            'size_gb' => 3.3,
            'ram_required_gb' => 8,
            'description' => 'Google efficient model. Good all-rounder.',
            'tags' => ['chat', 'efficient'],
            'recommended' => false,
        ],
        [
            'name' => 'deepseek-r1:7b',
            'display_name' => 'DeepSeek R1 7B',
            'size_gb' => 4.7,
            'ram_required_gb' => 8,
            'description' => 'Deep reasoning and analysis tasks.',
            'tags' => ['reasoning', 'analysis'],
            'recommended' => false,
        ],
    ],
];
