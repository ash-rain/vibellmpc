<div class="space-y-6">
    {{-- Welcome --}}
    <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-6">
        <h2 class="text-xl font-semibold text-white">VibeLLMPC</h2>
        <p class="text-gray-400 text-sm mt-1">Your private AI server is running locally.</p>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- AI Models --}}
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-5">
            <div class="text-gray-500 text-sm">AI Models</div>
            <div class="text-2xl font-bold text-white mt-1">{{ $installedModelCount }}</div>
            @if ($defaultModel)
                <div class="text-xs text-gray-600 mt-1 truncate">Default: {{ $defaultModel }}</div>
            @endif
        </div>

        {{-- Ollama --}}
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-5">
            <div class="text-gray-500 text-sm">Ollama</div>
            <div class="mt-2">
                @if ($ollamaStatus === 'running')
                    <span class="inline-flex items-center gap-1.5 text-xs bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        Running
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-xs bg-gray-500/20 text-gray-400 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full"></span>
                        Stopped
                    </span>
                @endif
            </div>
        </div>

        {{-- Tunnel --}}
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-5">
            <div class="text-gray-500 text-sm">Tunnel</div>
            <div class="mt-2">
                @if ($tunnelRunning && $tunnelSubdomain)
                    <a href="https://{{ $tunnelSubdomain }}.vibellmpc.com" target="_blank"
                        class="inline-flex items-center gap-1.5 text-xs bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full hover:bg-emerald-500/30 transition-colors">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        Online
                    </a>
                @elseif ($tunnelRunning)
                    <span class="inline-flex items-center gap-1.5 text-xs bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                        Online
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-xs bg-gray-500/20 text-gray-400 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full"></span>
                        Offline
                    </span>
                @endif
            </div>
        </div>

        {{-- Workflows --}}
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-5">
            <div class="text-gray-500 text-sm">Workflows (n8n)</div>
            <div class="mt-2">
                @if ($n8nEnabled)
                    <span class="inline-flex items-center gap-1.5 text-xs bg-purple-500/20 text-purple-400 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 bg-purple-400 rounded-full"></span>
                        Enabled
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 text-xs bg-gray-500/20 text-gray-400 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full"></span>
                        Disabled
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-6">
        <h3 class="text-sm font-medium text-gray-400 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('dashboard.chat') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-gray-950 font-medium text-sm rounded-xl transition-colors">
                Open Chat
            </a>
            <a href="{{ route('dashboard.models') }}" class="px-4 py-2 bg-white/[0.06] hover:bg-white/10 text-white text-sm rounded-lg transition-colors">
                Manage Models
            </a>
            <a href="{{ route('dashboard.workflows') }}" class="px-4 py-2 bg-white/[0.06] hover:bg-white/10 text-white text-sm rounded-lg transition-colors">
                Workflows
            </a>
            <a href="{{ route('dashboard.api') }}" class="px-4 py-2 bg-white/[0.06] hover:bg-white/10 text-white text-sm rounded-lg transition-colors">
                API Access
            </a>
            <a href="{{ route('dashboard.tunnels') }}" class="px-4 py-2 bg-white/[0.06] hover:bg-white/10 text-white text-sm rounded-lg transition-colors">
                Tunnel Settings
            </a>
        </div>
    </div>
</div>
