<?php

declare(strict_types=1);

namespace App\Services\Ollama;

use Illuminate\Support\Facades\Http;

class OllamaApiClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.ollama.url', 'http://ollama:11434');
    }

    /**
     * List all locally available models.
     *
     * @return array<int, array<string, mixed>>
     */
    public function listModels(): array
    {
        $response = Http::get("{$this->baseUrl}/api/tags");

        return $response->json('models', []);
    }

    /**
     * Pull a model from the Ollama registry (blocking, no stream).
     */
    public function pullModel(string $name): void
    {
        Http::timeout(600)->post("{$this->baseUrl}/api/pull", [
            'name' => $name,
            'stream' => false,
        ])->throw();
    }

    /**
     * Delete a locally installed model.
     */
    public function deleteModel(string $name): bool
    {
        $response = Http::delete("{$this->baseUrl}/api/delete", [
            'name' => $name,
        ]);

        return $response->successful();
    }

    /**
     * Send a chat completion request.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @return array<string, mixed>
     */
    public function chat(string $model, array $messages): array
    {
        return Http::post("{$this->baseUrl}/api/chat", [
            'model' => $model,
            'messages' => $messages,
            'stream' => false,
        ])->throw()->json();
    }

    /**
     * Check whether the Ollama daemon is reachable.
     */
    public function isRunning(): bool
    {
        try {
            Http::timeout(3)->get("{$this->baseUrl}/api/tags")->throw();

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
