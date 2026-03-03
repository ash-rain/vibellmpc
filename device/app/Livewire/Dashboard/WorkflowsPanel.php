<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\DeviceState;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.dashboard', ['title' => 'Workflows'])]
#[Title('Workflows — VibeLLMPC')]
class WorkflowsPanel extends Component
{
    public bool $n8nEnabled = false;

    public string $n8nUrl = 'http://vibellmpc.local:5678';

    public function mount(): void
    {
        $this->n8nEnabled = DeviceState::getValue('n8n_enabled') === 'true';
    }

    public function enableN8n(): void
    {
        shell_exec('docker compose up -d n8n 2>&1');
        DeviceState::setValue('n8n_enabled', 'true');
        $this->n8nEnabled = true;
    }

    public function disableN8n(): void
    {
        shell_exec('docker compose stop n8n 2>&1');
        DeviceState::setValue('n8n_enabled', 'false');
        $this->n8nEnabled = false;
    }

    public function render()
    {
        return view('livewire.dashboard.workflows-panel');
    }
}
