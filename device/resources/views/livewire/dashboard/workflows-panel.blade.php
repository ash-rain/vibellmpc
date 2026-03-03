<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Workflows</h1>
            <p class="text-gray-400 text-sm mt-1">Build AI automations with n8n — no code required.</p>
        </div>
        @if ($n8nEnabled)
            <div class="flex items-center gap-3">
                <a
                    href="{{ $n8nUrl }}"
                    target="_blank"
                    class="inline-flex items-center gap-2 text-sm text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 px-4 py-2 rounded-lg transition-all"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Open full screen
                </a>
                <button
                    wire:click="disableN8n"
                    wire:confirm="Stop n8n? All running workflows will be paused."
                    class="inline-flex items-center gap-2 text-sm text-red-400 hover:text-red-300 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 px-4 py-2 rounded-lg transition-all"
                >
                    Disable
                </button>
            </div>
        @endif
    </div>

    @if (! $n8nEnabled)
        {{-- Disabled state --}}
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-10 text-center">
            <div class="w-16 h-16 mx-auto bg-purple-500/10 border border-purple-500/20 rounded-full flex items-center justify-center mb-5">
                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h2 class="text-xl font-semibold text-white mb-2">Build AI Automations</h2>
            <p class="text-gray-400 text-sm mb-2 max-w-md mx-auto">
                n8n lets you connect your local AI models to external services — email, Slack, calendars, databases — with a visual drag-and-drop editor. No code needed.
            </p>
            <p class="text-gray-500 text-xs mb-6">n8n runs locally on your VibeLLMPC device on port 5678.</p>
            <button
                wire:click="enableN8n"
                class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-colors font-medium"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>
                Enable n8n
            </button>
        </div>

        {{-- Template previews (informational) --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Example Workflows</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([
                    ['title' => 'Summarise Emails', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'desc' => 'Send your inbox through a local LLM to get concise daily summaries.', 'color' => 'blue'],
                    ['title' => 'Daily News Digest', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h6', 'desc' => 'Fetch RSS feeds and have your AI summarise the top stories each morning.', 'color' => 'yellow'],
                    ['title' => 'Chat with Documents', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'desc' => 'Upload PDFs and query them with natural language through your local model.', 'color' => 'emerald'],
                ] as $template)
                    <div class="bg-white/[0.03] rounded-xl border border-white/[0.08] p-5">
                        <div class="w-9 h-9 bg-{{ $template['color'] }}-500/10 border border-{{ $template['color'] }}-500/20 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-{{ $template['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $template['icon'] }}" />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold text-sm mb-1">{{ $template['title'] }}</h3>
                        <p class="text-gray-400 text-xs leading-relaxed">{{ $template['desc'] }}</p>
                        <span class="inline-block mt-3 text-xs text-gray-600 border border-white/5 px-2 py-0.5 rounded">Enable n8n to use</span>
                    </div>
                @endforeach
            </div>
        </div>

    @else
        {{-- Enabled — show iframe --}}
        <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] overflow-hidden" style="height: calc(100vh - 260px); min-height: 520px;">
            <iframe
                src="{{ $n8nUrl }}"
                class="w-full h-full border-0"
                title="n8n Workflow Editor"
            ></iframe>
        </div>

        {{-- Template cards below iframe --}}
        <div>
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Starter Workflow Templates</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ([
                    ['title' => 'Summarise Emails', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'desc' => 'Send your inbox through a local LLM to get concise daily summaries.', 'color' => 'blue'],
                    ['title' => 'Daily News Digest', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h6', 'desc' => 'Fetch RSS feeds and have your AI summarise the top stories each morning.', 'color' => 'yellow'],
                    ['title' => 'Chat with Documents', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'desc' => 'Upload PDFs and query them with natural language through your local model.', 'color' => 'emerald'],
                ] as $template)
                    <div class="bg-white/[0.03] rounded-xl border border-white/[0.08] p-5">
                        <div class="w-9 h-9 bg-{{ $template['color'] }}-500/10 border border-{{ $template['color'] }}-500/20 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-{{ $template['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $template['icon'] }}" />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold text-sm mb-1">{{ $template['title'] }}</h3>
                        <p class="text-gray-400 text-xs leading-relaxed">{{ $template['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
