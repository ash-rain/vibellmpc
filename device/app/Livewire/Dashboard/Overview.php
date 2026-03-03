<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\OllamaModelStatus;
use App\Models\DeviceState;
use App\Models\OllamaModel;
use App\Models\TunnelConfig;
use App\Services\Tunnel\TunnelService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.dashboard', ['title' => 'Overview'])]
#[Title('Overview — VibeLLMPC')]
class Overview extends Component
{
    public int $installedModelCount = 0;

    public ?string $defaultModel = null;

    public bool $tunnelRunning = false;

    public ?string $tunnelSubdomain = null;

    public bool $n8nEnabled = false;

    public string $ollamaStatus = 'unknown';

    public function mount(TunnelService $tunnelService): void
    {
        $this->installedModelCount = OllamaModel::where('status', OllamaModelStatus::Installed)->count();
        $this->defaultModel = DeviceState::getValue('default_model');
        $this->tunnelRunning = $tunnelService->isRunning();
        $this->n8nEnabled = DeviceState::getValue('n8n_enabled') === 'true';

        $tunnelConfig = TunnelConfig::current();
        $this->tunnelSubdomain = $tunnelConfig?->subdomain;

        $this->ollamaStatus = $this->checkOllama();
    }

    private function checkOllama(): string
    {
        $result = @file_get_contents('http://localhost:11434/');

        return $result !== false ? 'running' : 'stopped';
    }

    public function render()
    {
        return view('livewire.dashboard.overview');
    }
}
