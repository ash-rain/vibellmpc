<div class="space-y-6">
    {{-- Hero --}}
    <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-8 text-center">
        <div class="mb-6">
            <div class="w-20 h-20 mx-auto bg-emerald-500/10 border border-emerald-500/20 rounded-full flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">You're all set!</h2>
            <p class="text-gray-400">Your VibeLLMPC private AI server is ready to use.</p>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Installed Models --}}
        <div class="bg-white/[0.03] rounded-xl border border-white/[0.08] p-5">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">AI Models</span>
            </div>
            @if ($installedModels->isEmpty())
                <p class="text-sm text-gray-500">No models installed yet.</p>
            @else
                <p class="text-2xl font-bold text-white mb-2">{{ $installedModels->count() }}</p>
                <div class="space-y-1">
                    @foreach ($installedModels as $model)
                        <div class="text-xs text-gray-400 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            {{ $model->display_name }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tunnel URL --}}
        <div class="bg-white/[0.03] rounded-xl border border-white/[0.08] p-5">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tunnel URL</span>
            </div>
            @if ($tunnelSubdomain)
                <a
                    href="https://{{ $tunnelSubdomain }}.vibellmpc.com"
                    target="_blank"
                    class="text-sm text-emerald-400 hover:text-emerald-300 transition-colors break-all"
                >
                    {{ $tunnelSubdomain }}.vibellmpc.com
                    <svg class="w-3 h-3 inline ml-0.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            @else
                <p class="text-sm text-gray-500">Not configured</p>
                <p class="text-xs text-gray-600 mt-1">Set up a tunnel to access remotely.</p>
            @endif
        </div>

        {{-- n8n Status --}}
        <div class="bg-white/[0.03] rounded-xl border border-white/[0.08] p-5">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Workflows (n8n)</span>
            </div>
            @if ($n8nEnabled)
                <span class="inline-flex items-center gap-1.5 text-sm text-emerald-400">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                    Enabled
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-sm text-gray-500">
                    <span class="w-2 h-2 bg-gray-600 rounded-full"></span>
                    Disabled
                </span>
                <p class="text-xs text-gray-600 mt-1">Enable from the Workflows panel.</p>
            @endif
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-6">
        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

            {{-- Open Chat --}}
            @php
                $chatUrl = $tunnelSubdomain
                    ? 'https://' . $tunnelSubdomain . '.vibellmpc.com/chat'
                    : 'http://vibellmpc.local:3000';
            @endphp
            <a href="{{ $chatUrl }}" target="_blank"
                class="bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-white/20 rounded-xl p-4 flex flex-col items-center gap-2 transition-all group">
                <svg class="w-7 h-7 text-emerald-400 group-hover:text-emerald-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="text-sm text-gray-300 font-medium">Open Chat</span>
                <span class="text-xs text-gray-500">Open WebUI</span>
            </a>

            {{-- Manage Models --}}
            <a href="{{ route('dashboard.models') }}"
                class="bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-white/20 rounded-xl p-4 flex flex-col items-center gap-2 transition-all group">
                <svg class="w-7 h-7 text-emerald-400 group-hover:text-emerald-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="text-sm text-gray-300 font-medium">Manage Models</span>
                <span class="text-xs text-gray-500">Add or remove AI models</span>
            </a>

            {{-- Open Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="bg-white/[0.04] hover:bg-white/[0.08] border border-white/10 hover:border-white/20 rounded-xl p-4 flex flex-col items-center gap-2 transition-all group">
                <svg class="w-7 h-7 text-emerald-400 group-hover:text-emerald-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                </svg>
                <span class="text-sm text-gray-300 font-medium">Open Dashboard</span>
                <span class="text-xs text-gray-500">Full device control</span>
            </a>
        </div>
    </div>

    {{-- Go to Dashboard --}}
    <button
        wire:click="goToDashboard"
        class="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-gray-950 font-semibold rounded-xl transition-colors"
    >
        Go to Dashboard &rarr;
    </button>
</div>
