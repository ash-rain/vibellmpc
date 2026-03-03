<div class="space-y-6">
    <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] p-8">
        <h2 class="text-xl font-semibold text-white mb-2">Cloudflare Tunnel</h2>
        <p class="text-gray-400 text-sm mb-6">
            Expose your VibeLLMPC device securely over the internet with a Cloudflare tunnel.
            This lets you access Open WebUI and n8n from anywhere without port forwarding.
        </p>

        {{-- Status card --}}
        <div class="bg-white/[0.03] rounded-xl border border-white/[0.08] p-5 space-y-3 mb-6">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-300">Tunnel status</span>
                @if ($tunnelRunning)
                    <span class="text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full">Running</span>
                @else
                    <span class="text-xs bg-white/5 text-gray-500 border border-white/10 px-2 py-0.5 rounded-full">Not running</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-300">Credentials</span>
                @if ($tunnelConfigured)
                    <span class="text-xs bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-2 py-0.5 rounded-full">Configured</span>
                @else
                    <span class="text-xs bg-amber-500/20 text-amber-400 border border-amber-500/30 px-2 py-0.5 rounded-full">Not configured</span>
                @endif
            </div>
        </div>

        <p class="text-xs text-gray-500">
            You can configure the tunnel later from the dashboard. A Cloudflare account and tunnel token are required.
        </p>
    </div>

    {{-- Footer actions --}}
    <div class="flex justify-between items-center">
        <button
            wire:click="skip"
            class="text-sm text-gray-500 hover:text-gray-300 transition-colors"
        >
            Skip for now
        </button>

        <button
            wire:click="complete"
            class="px-5 py-2 text-sm font-medium rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white transition-all"
        >
            Continue
        </button>
    </div>
</div>
