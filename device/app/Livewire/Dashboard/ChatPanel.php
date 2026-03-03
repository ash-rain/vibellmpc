<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\OllamaModelStatus;
use App\Models\DeviceState;
use App\Models\OllamaModel;
use App\Models\TunnelConfig;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.dashboard', ['title' => 'Chat'])]
#[Title('Chat — VibeLLMPC')]
class ChatPanel extends Component
{
    public string $webUiUrl = '';

    public bool $isRunning = false;

    public int $installedModelCount = 0;

    public bool $tunnelActive = false;

    public function mount(): void
    {
        $tunnelConfig = TunnelConfig::current();
        $subdomain = $tunnelConfig?->subdomain;

        $this->webUiUrl = $subdomain
            ? "https://{$subdomain}.vibellmpc.com/chat"
            : 'http://vibellmpc.local:3000';

        $this->isRunning = $this->checkOpenWebUi();
        $this->installedModelCount = OllamaModel::where('status', OllamaModelStatus::Installed)->count();
        $this->tunnelActive = (bool) $subdomain;
    }

    public function refresh(): void
    {
        $this->isRunning = $this->checkOpenWebUi();
    }

    private function checkOpenWebUi(): bool
    {
        $result = shell_exec('docker inspect --format="{{.State.Running}}" open-webui 2>/dev/null');

        return trim((string) $result) === 'true';
    }

    public function render()
    {
        return view('livewire.dashboard.chat-panel');
    }
}
