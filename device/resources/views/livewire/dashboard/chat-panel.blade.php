<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Chat</h1>
            <p class="text-gray-400 text-sm mt-1">Chat with your locally-hosted AI models via Open WebUI.</p>
        </div>
        @if ($isRunning)
            <a
                href="{{ $webUiUrl }}"
                target="_blank"
                class="inline-flex items-center gap-2 text-sm text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 px-4 py-2 rounded-lg transition-all"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                Open full screen
            </a>
        @endif
    </div>

    {{-- Main content --}}
    @if ($isRunning)
        {{-- Chat iframe --}}
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] overflow-hidden" style="height: calc(100vh - 280px); min-height: 500px;">
            <iframe
                src="{{ $webUiUrl }}"
                class="w-full h-full border-0"
                title="Open WebUI"
                allow="microphone"
            ></iframe>
        </div>

        {{-- Stats strip --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-white/[0.02] rounded-xl border border-white/[0.06] px-4 py-3 flex items-center gap-3">
                <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                <div>
                    <div class="text-xs text-gray-500">Status</div>
                    <div class="text-sm text-white font-medium">Running</div>
                </div>
            </div>
            <div class="bg-white/[0.02] rounded-xl border border-white/[0.06] px-4 py-3 flex items-center gap-3">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <div>
                    <div class="text-xs text-gray-500">Models</div>
                    <div class="text-sm text-white font-medium">{{ $installedModelCount }} installed</div>
                </div>
            </div>
            <div class="bg-white/[0.02] rounded-xl border border-white/[0.06] px-4 py-3 flex items-center gap-3">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3" />
                </svg>
                <div>
                    <div class="text-xs text-gray-500">Tunnel</div>
                    <div class="text-sm text-white font-medium">{{ $tunnelActive ? 'Active' : 'Local only' }}</div>
                </div>
            </div>
        </div>
    @else
        {{-- Not running state --}}
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-12 text-center">
            <div class="w-16 h-16 mx-auto bg-yellow-500/10 border border-yellow-500/20 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-yellow-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-white mb-2">Open WebUI is starting…</h2>
            <p class="text-gray-400 text-sm mb-6">The chat interface is initialising. This usually takes 20–30 seconds on first boot.</p>
            <button
                wire:click="refresh"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 hover:text-white rounded-xl transition-all text-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Check again
            </button>
        </div>
    @endif
</div>
