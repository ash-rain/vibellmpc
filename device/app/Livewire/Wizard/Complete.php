<?php

declare(strict_types=1);

namespace App\Livewire\Wizard;

use App\Enums\OllamaModelStatus;
use App\Models\DeviceState;
use App\Models\OllamaModel;
use App\Models\TunnelConfig;
use App\Services\DeviceStateService;
use App\Services\WizardProgressService;
use Illuminate\Support\Collection;
use Livewire\Component;
use VibellmPC\Common\Enums\WizardStep;

class Complete extends Component
{
    /** @var Collection<int, OllamaModel> */
    public Collection $installedModels;

    public ?string $tunnelSubdomain = null;

    public bool $n8nEnabled = false;

    public function mount(): void
    {
        $this->installedModels = OllamaModel::where('status', OllamaModelStatus::Installed)->get();

        $tunnelConfig = TunnelConfig::current();
        if ($tunnelConfig && $tunnelConfig->subdomain) {
            $this->tunnelSubdomain = $tunnelConfig->subdomain;
        }

        $this->n8nEnabled = DeviceState::getValue('n8n_enabled') === 'true';
    }

    public function goToDashboard(WizardProgressService $progressService, DeviceStateService $stateService): void
    {
        $progressService->completeStep(WizardStep::Complete);
        $stateService->setMode(DeviceStateService::MODE_DASHBOARD);

        $this->redirect(route('home'));
    }

    public function render()
    {
        return view('livewire.wizard.complete');
    }
}
