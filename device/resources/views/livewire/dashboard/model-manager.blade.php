<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white">AI Models</h1>
        <p class="text-gray-400 text-sm mt-1">Manage locally-hosted AI models served by Ollama.</p>
    </div>

    {{-- Downloading --}}
    @if ($downloadingModels->isNotEmpty())
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-6">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Downloading</h2>
            <div class="space-y-3">
                @foreach ($downloadingModels as $model)
                    <div class="flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="text-white font-medium">{{ $model->display_name }}</span>
                                <span class="text-gray-400">{{ $model->progress }}%</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-1.5">
                                <div
                                    class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500"
                                    style="width: {{ $model->progress }}%"
                                ></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Installed models --}}
    <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] overflow-hidden">
        <div class="px-6 py-4 border-b border-white/[0.06] flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Installed Models</h2>
            <span class="text-xs text-gray-500">{{ $installedModels->count() }} installed</span>
        </div>

        @if ($installedModels->isEmpty())
            <div class="px-6 py-10 text-center">
                <svg class="w-10 h-10 text-gray-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-500 text-sm">No models installed. Download one below.</p>
            </div>
        @else
            <div class="divide-y divide-white/[0.04]">
                @foreach ($installedModels as $model)
                    <div class="px-6 py-4 flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-white font-medium text-sm">{{ $model->display_name }}</span>
                                @if ($defaultModel === $model->model_name)
                                    <span class="text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full font-medium">
                                        Default
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 mt-1 text-xs text-gray-500">
                                <span>{{ $model->size_gb }} GB</span>
                                <span>{{ $model->ram_required_gb }} GB RAM</span>
                                @if ($model->pulled_at)
                                    <span>Installed {{ $model->pulled_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if ($defaultModel !== $model->model_name)
                                <button
                                    wire:click="setDefault('{{ $model->model_name }}')"
                                    class="text-xs text-gray-500 hover:text-gray-300 transition-colors px-2 py-1 rounded"
                                    title="Set as default"
                                >
                                    Set default
                                </button>
                            @endif
                            <button
                                wire:click="deleteModel('{{ $model->model_name }}')"
                                wire:confirm="Remove {{ $model->display_name }}? This will free up {{ $model->size_gb }} GB."
                                class="text-xs text-red-500/70 hover:text-red-400 transition-colors px-2 py-1 rounded"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Available models --}}
    @if (!empty($availableModels))
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-6">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Available Models</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($availableModels as $model)
                    <div class="bg-white/[0.03] rounded-xl border border-white/[0.08] p-5 flex flex-col gap-3 relative">
                        @if ($model['recommended'])
                            <span class="absolute top-3 right-3 text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full font-medium">
                                Recommended
                            </span>
                        @endif
                        <div class="pr-24">
                            <h3 class="text-white font-semibold text-sm">{{ $model['display_name'] }}</h3>
                            <p class="text-gray-400 text-xs mt-1 leading-relaxed">{{ $model['description'] }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <span>{{ $model['size_gb'] }} GB</span>
                            <span>{{ $model['ram_required_gb'] }} GB RAM</span>
                        </div>
                        @if (!empty($model['tags']))
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($model['tags'] as $tag)
                                    <span class="text-xs bg-white/5 border border-white/10 text-gray-400 px-2 py-0.5 rounded-full">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                        <div class="mt-auto pt-2">
                            <button
                                wire:click="downloadModel('{{ $model['name'] }}')"
                                class="inline-flex items-center gap-1.5 text-xs bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 text-gray-300 hover:text-white px-3 py-1.5 rounded-lg font-medium transition-all"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Disk info --}}
    @if ($diskInfo)
        <div class="bg-white/[0.02] rounded-xl border border-white/[0.06] px-5 py-3 flex items-center gap-3">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 7v10c0 1.1.9 2 2 2h12a2 2 0 002-2V7M4 7l8-4 8 4M4 7h16" />
            </svg>
            <span class="text-xs text-gray-500">Disk: {{ $diskInfo }}</span>
        </div>
    @endif
</div>
