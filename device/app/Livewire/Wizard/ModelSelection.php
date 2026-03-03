<?php

declare(strict_types=1);

namespace App\Livewire\Wizard;

use App\Enums\OllamaModelStatus;
use App\Jobs\PullModelJob;
use App\Models\OllamaModel;
use App\Services\WizardProgressService;
use Livewire\Attributes\On;
use Livewire\Component;
use VibellmPC\Common\Enums\WizardStep;

class ModelSelection extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $models = [];

    /** @var array<string, OllamaModel> */
    public array $installedModels = [];

    /** @var array<int, string> */
    public array $downloading = [];

    public function mount(): void
    {
        $this->models = config('models.catalogue', []);
        $this->loadInstalledModels();
    }

    #[On('model-pull-complete')]
    public function refresh(): void
    {
        $this->loadInstalledModels();
    }

    public function downloadModel(string $name): void
    {
        if ($this->isInstalled($name) || in_array($name, $this->downloading, true)) {
            return;
        }

        $catalogue = collect($this->models)->firstWhere('name', $name);

        $record = OllamaModel::firstOrCreate(
            ['model_name' => $name],
            [
                'display_name' => $catalogue['display_name'] ?? $name,
                'size_gb' => $catalogue['size_gb'] ?? 0,
                'ram_required_gb' => $catalogue['ram_required_gb'] ?? 0,
                'description' => $catalogue['description'] ?? null,
                'tags' => $catalogue['tags'] ?? null,
                'status' => OllamaModelStatus::Available,
            ]
        );

        $this->downloading[] = $name;

        PullModelJob::dispatch($name, $record->id);

        $this->loadInstalledModels();
    }

    public function getProgress(string $name): int
    {
        return OllamaModel::where('model_name', $name)->value('progress') ?? 0;
    }

    public function isInstalled(string $name): bool
    {
        $model = OllamaModel::where('model_name', $name)->first();

        return $model?->status === OllamaModelStatus::Installed;
    }

    public function skip(WizardProgressService $progressService): void
    {
        $progressService->completeStep(WizardStep::ModelSelection);
        $this->dispatch('step-skipped');
    }

    public function complete(WizardProgressService $progressService): void
    {
        $progressService->completeStep(WizardStep::ModelSelection);
        $this->dispatch('step-completed');
    }

    public function render()
    {
        $this->loadInstalledModels();

        return view('livewire.wizard.model-selection');
    }

    private function loadInstalledModels(): void
    {
        $records = OllamaModel::whereIn('model_name', collect($this->models)->pluck('name')->all())->get();

        $this->installedModels = $records->keyBy('model_name')->all();

        $this->downloading = $records
            ->where('status', OllamaModelStatus::Downloading)
            ->pluck('model_name')
            ->all();
    }
}
