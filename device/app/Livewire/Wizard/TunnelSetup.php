<?php

declare(strict_types=1);

namespace App\Livewire\Wizard;

use App\Services\Tunnel\TunnelService;
use App\Services\WizardProgressService;
use Livewire\Component;
use VibellmPC\Common\Enums\WizardStep;

class TunnelSetup extends Component
{
    public bool $tunnelRunning = false;

    public bool $tunnelConfigured = false;

    public function mount(TunnelService $tunnelService): void
    {
        $this->tunnelRunning = $tunnelService->isRunning();
        $this->tunnelConfigured = $tunnelService->hasCredentials();
    }

    public function skip(WizardProgressService $progressService): void
    {
        $progressService->completeStep(WizardStep::TunnelSetup);
        $this->dispatch('step-skipped');
    }

    public function complete(WizardProgressService $progressService): void
    {
        $progressService->completeStep(WizardStep::TunnelSetup);
        $this->dispatch('step-completed');
    }

    public function render()
    {
        return view('livewire.wizard.tunnel-setup');
    }
}
