<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\OllamaModelStatus;
use App\Models\OllamaModel;
use App\Services\Ollama\OllamaApiClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class PullModelJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 600;

    public function __construct(
        public string $modelName,
        public int $ollamaModelId,
    ) {}

    public function handle(OllamaApiClient $client): void
    {
        $model = OllamaModel::find($this->ollamaModelId);

        if (! $model) {
            Log::warning('PullModelJob: OllamaModel not found', ['id' => $this->ollamaModelId]);

            return;
        }

        $model->update(['status' => OllamaModelStatus::Downloading, 'progress' => 0]);

        try {
            $client->pullModel($this->modelName);

            $model->update([
                'status' => OllamaModelStatus::Installed,
                'progress' => 100,
                'pulled_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::error('PullModelJob failed', [
                'model' => $this->modelName,
                'error' => $e->getMessage(),
            ]);

            $model->update(['status' => OllamaModelStatus::Error]);
        }
    }
}
