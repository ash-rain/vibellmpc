<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\OllamaModelStatus;
use App\Jobs\PullModelJob;
use App\Models\DeviceState;
use App\Models\OllamaModel;
use App\Services\Ollama\OllamaApiClient;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Poll;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.dashboard', ['title' => 'AI Models'])]
#[Title('AI Models — VibeLLMPC')]
class ModelManager extends Component
{
    /** @var Collection<int, OllamaModel> */
    public Collection $installedModels;

    /** @var array<int, array<string, mixed>> */
    public array $availableModels = [];

    /** @var Collection<int, OllamaModel> */
    public Collection $downloadingModels;

    public ?string $defaultModel = null;

    public ?string $diskInfo = null;

    public function mount(): void
    {
        $this->loadModels();
        $this->diskInfo = $this->getDiskInfo();
    }

    #[Poll(2000)]
    public function poll(): void
    {
        $this->loadModels();
    }

    private function loadModels(): void
    {
        $this->installedModels = OllamaModel::where('status', OllamaModelStatus::Installed)->get();
        $this->downloadingModels = OllamaModel::where('status', OllamaModelStatus::Downloading)->get();
        $this->defaultModel = DeviceState::getValue('default_model');

        $installedNames = $this->installedModels->pluck('model_name')->merge(
            $this->downloadingModels->pluck('model_name')
        )->all();

        $this->availableModels = collect(config('models.catalogue'))
            ->filter(fn (array $m) => ! in_array($m['name'], $installedNames, true))
            ->values()
            ->all();
    }

    public function downloadModel(string $name): void
    {
        $catalogue = collect(config('models.catalogue'))->firstWhere('name', $name);
        if (! $catalogue) {
            return;
        }

        $model = OllamaModel::firstOrCreate(
            ['model_name' => $name],
            [
                'display_name' => $catalogue['display_name'],
                'size_gb' => $catalogue['size_gb'],
                'ram_required_gb' => $catalogue['ram_required_gb'],
                'description' => $catalogue['description'],
                'tags' => $catalogue['tags'],
                'status' => OllamaModelStatus::Downloading,
                'progress' => 0,
            ]
        );

        $model->update(['status' => OllamaModelStatus::Downloading, 'progress' => 0]);

        PullModelJob::dispatch($model->id);

        $this->loadModels();
    }

    public function deleteModel(string $name, OllamaApiClient $client): void
    {
        $client->deleteModel($name);

        OllamaModel::where('model_name', $name)->update([
            'status' => OllamaModelStatus::Available,
            'progress' => 0,
            'pulled_at' => null,
        ]);

        if ($this->defaultModel === $name) {
            DeviceState::setValue('default_model', null);
        }

        $this->loadModels();
    }

    public function setDefault(string $name): void
    {
        DeviceState::setValue('default_model', $name);
        $this->defaultModel = $name;
    }

    private function getDiskInfo(): string
    {
        $result = shell_exec('df -h / 2>/dev/null | tail -1');
        if (! $result) {
            return 'N/A';
        }

        $parts = preg_split('/\s+/', trim($result));

        return isset($parts[1], $parts[2], $parts[3])
            ? "Used: {$parts[2]} / Total: {$parts[1]} (Free: {$parts[3]})"
            : 'N/A';
    }

    public function render()
    {
        return view('livewire.dashboard.model-manager');
    }
}
