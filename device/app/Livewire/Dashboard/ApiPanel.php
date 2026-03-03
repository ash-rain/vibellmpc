<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\OllamaModelStatus;
use App\Models\ApiKey;
use App\Models\OllamaModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.dashboard', ['title' => 'API'])]
#[Title('API — VibeLLMPC')]
class ApiPanel extends Component
{
    public string $ollamaEndpoint = 'http://vibellmpc.local:11434';

    public string $openAiCompatEndpoint = 'http://vibellmpc.local:11434/v1';

    /** @var Collection<int, OllamaModel> */
    public Collection $installedModels;

    /** @var Collection<int, ApiKey> */
    public Collection $apiKeys;

    public string $newKeyName = '';

    public string $activeTab = 'curl';

    public function mount(): void
    {
        $this->installedModels = OllamaModel::where('status', OllamaModelStatus::Installed)->get();
        $this->apiKeys = ApiKey::latest()->get();
    }

    public function generateKey(): void
    {
        $name = trim($this->newKeyName);
        if (! $name) {
            return;
        }

        ApiKey::create([
            'name' => $name,
            'key' => 'vllm_' . Str::random(28),
        ]);

        $this->newKeyName = '';
        $this->apiKeys = ApiKey::latest()->get();
    }

    public function revokeKey(int $id): void
    {
        ApiKey::destroy($id);
        $this->apiKeys = ApiKey::latest()->get();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.dashboard.api-panel');
    }
}
