<div class="space-y-6">
    <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-8">
        <h2 class="text-xl font-semibold text-white mb-2">Choose Your AI Models</h2>
        <p class="text-gray-400 text-sm mb-6">
            Select one or more models to download onto your VibeLLMPC device.
            Models are served locally via Ollama — no internet needed after download.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($models as $model)
                @php
                    $name = $model['name'];
                    $record = $installedModels[$name] ?? null;
                    $status = $record?->status;
                    $isInstalled = $status?->value === 'installed';
                    $isDownloading = $status?->value === 'downloading' || in_array($name, $downloading);
                    $progress = $record?->progress ?? 0;
                @endphp

                <div class="bg-white/[0.03] rounded-xl border border-white/[0.08] p-5 flex flex-col gap-3 relative">

                    {{-- Recommended badge --}}
                    @if ($model['recommended'])
                        <span class="absolute top-3 right-3 text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full font-medium">
                            Recommended
                        </span>
                    @endif

                    {{-- Model name & description --}}
                    <div class="pr-24">
                        <h3 class="text-white font-semibold text-sm">{{ $model['display_name'] }}</h3>
                        <p class="text-gray-400 text-xs mt-1 leading-relaxed">{{ $model['description'] }}</p>
                    </div>

                    {{-- Meta: size + RAM --}}
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 1.1.9 2 2 2h12a2 2 0 002-2V7M4 7l8-4 8 4M4 7h16" />
                            </svg>
                            {{ $model['size_gb'] }} GB
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2h-4M9 3v4M15 3v4M9 7h6" />
                            </svg>
                            {{ $model['ram_required_gb'] }} GB RAM
                        </span>
                    </div>

                    {{-- Tags --}}
                    @if (!empty($model['tags']))
                        <div class="flex flex-wrap gap-1.5">
                            @foreach ($model['tags'] as $tag)
                                <span class="text-xs bg-white/5 border border-white/10 text-gray-400 px-2 py-0.5 rounded-full">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Action area --}}
                    <div class="mt-auto pt-2">
                        @if ($isInstalled)
                            <span class="inline-flex items-center gap-1.5 text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-3 py-1.5 rounded-lg font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Installed
                            </span>

                        @elseif ($isDownloading)
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs text-gray-400">
                                    <span>Downloading…</span>
                                    <span>{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-1.5">
                                    <div
                                        class="bg-emerald-500 h-1.5 rounded-full transition-all duration-500"
                                        style="width: {{ $progress }}%"
                                    ></div>
                                </div>
                            </div>

                        @else
                            <button
                                wire:click="downloadModel('{{ $name }}')"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-1.5 text-xs bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 text-gray-300 hover:text-white px-3 py-1.5 rounded-lg font-medium transition-all"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Footer actions --}}
    <div class="flex justify-between items-center">
        <button
            wire:click="skip"
            class="text-sm text-gray-500 hover:text-gray-300 transition-colors"
        >
            Skip for now
        </button>

        @php
            $anyInstalled = collect($installedModels)->contains(fn ($m) => $m->status?->value === 'installed');
        @endphp

        <button
            wire:click="complete"
            @if (!$anyInstalled) disabled @endif
            class="px-5 py-2 text-sm font-medium rounded-xl transition-all
                {{ $anyInstalled
                    ? 'bg-emerald-600 hover:bg-emerald-500 text-white'
                    : 'bg-white/5 text-gray-600 cursor-not-allowed border border-white/5' }}"
        >
            Continue
        </button>
    </div>
</div>
