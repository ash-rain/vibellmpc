<div class="space-y-6" x-data="{ copiedEndpoint: null }">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-white">API Access</h1>
        <p class="text-gray-400 text-sm mt-1">Connect any OpenAI-compatible tool directly to your local AI server.</p>
    </div>

    {{-- Info box --}}
    <div class="bg-emerald-500/5 border border-emerald-500/20 rounded-xl px-5 py-4 flex gap-3">
        <svg class="w-5 h-5 text-emerald-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-sm text-emerald-300">
            Ollama speaks the OpenAI API — just change the <code class="bg-white/10 px-1 rounded text-xs">base_url</code> in any OpenAI SDK to point to your device and set any API key.
        </p>
    </div>

    {{-- Endpoint cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ([
            ['label' => 'Ollama API', 'url' => $ollamaEndpoint, 'desc' => 'Native Ollama REST API'],
            ['label' => 'OpenAI-Compatible', 'url' => $openAiCompatEndpoint, 'desc' => 'Drop-in replacement for OpenAI SDK'],
        ] as $ep)
            <div class="bg-white/[0.03] rounded-xl border border-white/[0.08] p-5">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-white font-semibold text-sm">{{ $ep['label'] }}</h3>
                        <p class="text-gray-500 text-xs mt-0.5">{{ $ep['desc'] }}</p>
                    </div>
                </div>
                <div
                    class="flex items-center gap-2 bg-gray-950 rounded-lg px-3 py-2 group cursor-pointer"
                    x-data="{ copied: false }"
                    @click="navigator.clipboard.writeText('{{ $ep['url'] }}'); copied = true; setTimeout(() => copied = false, 2000)"
                >
                    <code class="text-xs text-emerald-400 flex-1 font-mono">{{ $ep['url'] }}</code>
                    <span class="text-xs text-gray-600 group-hover:text-gray-400 transition-colors shrink-0" x-show="!copied">Copy</span>
                    <span class="text-xs text-emerald-400 shrink-0" x-show="copied" x-cloak>Copied!</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Code snippets --}}
    <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] overflow-hidden">
        <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-1">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mr-4">Code Examples</h2>
            @foreach ([['tab' => 'curl', 'label' => 'cURL'], ['tab' => 'python', 'label' => 'Python'], ['tab' => 'js', 'label' => 'JavaScript']] as $t)
                <button
                    wire:click="setTab('{{ $t['tab'] }}')"
                    class="text-xs px-3 py-1.5 rounded-md transition-colors {{ $activeTab === $t['tab'] ? 'bg-white/10 text-white' : 'text-gray-500 hover:text-gray-300' }}"
                >
                    {{ $t['label'] }}
                </button>
            @endforeach
        </div>

        @php $model = $installedModels->first()?->model_name ?? 'llama3.2:8b'; @endphp

        <div class="p-6">
            @if ($activeTab === 'curl')
                <pre class="text-xs text-gray-300 font-mono bg-gray-950 rounded-xl p-4 overflow-x-auto leading-relaxed">curl {{ $openAiCompatEndpoint }}/chat/completions \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your-api-key" \
  -d '{
    "model": "{{ $model }}",
    "messages": [{"role": "user", "content": "Hello!"}]
  }'</pre>
            @elseif ($activeTab === 'python')
                <pre class="text-xs text-gray-300 font-mono bg-gray-950 rounded-xl p-4 overflow-x-auto leading-relaxed">from openai import OpenAI

client = OpenAI(
    base_url="{{ $openAiCompatEndpoint }}",
    api_key="your-api-key",
)

response = client.chat.completions.create(
    model="{{ $model }}",
    messages=[{"role": "user", "content": "Hello!"}]
)

print(response.choices[0].message.content)</pre>
            @else
                <pre class="text-xs text-gray-300 font-mono bg-gray-950 rounded-xl p-4 overflow-x-auto leading-relaxed">const response = await fetch(
  "{{ $openAiCompatEndpoint }}/chat/completions",
  {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Authorization": "Bearer your-api-key",
    },
    body: JSON.stringify({
      model: "{{ $model }}",
      messages: [{ role: "user", content: "Hello!" }],
    }),
  }
);

const data = await response.json();
console.log(data.choices[0].message.content);</pre>
            @endif
        </div>
    </div>

    {{-- API Keys --}}
    <div class="bg-white/[0.02] rounded-2xl border border-white/[0.06] overflow-hidden">
        <div class="px-6 py-4 border-b border-white/[0.06]">
            <h2 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">API Keys</h2>
            <p class="text-xs text-gray-600 mt-0.5">Keys are stored locally. Ollama accepts any string as a key.</p>
        </div>

        {{-- Keys table --}}
        @if ($apiKeys->isEmpty())
            <div class="px-6 py-8 text-center">
                <p class="text-sm text-gray-500">No API keys yet. Generate one below.</p>
            </div>
        @else
            <div class="divide-y divide-white/[0.04]">
                @foreach ($apiKeys as $apiKey)
                    <div class="px-6 py-4 flex items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="text-sm text-white font-medium">{{ $apiKey->name }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <code class="text-xs text-gray-500 font-mono">{{ substr($apiKey->key, 0, 12) }}••••••••••••••••••••</code>
                                <button
                                    x-data="{ copied: false }"
                                    @click="navigator.clipboard.writeText('{{ $apiKey->key }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="text-xs text-gray-600 hover:text-gray-400 transition-colors"
                                >
                                    <span x-show="!copied">Copy</span>
                                    <span x-show="copied" x-cloak class="text-emerald-400">Copied!</span>
                                </button>
                            </div>
                        </div>
                        <div class="text-xs text-gray-600 shrink-0">{{ $apiKey->created_at->diffForHumans() }}</div>
                        <button
                            wire:click="revokeKey({{ $apiKey->id }})"
                            wire:confirm="Revoke key '{{ $apiKey->name }}'?"
                            class="text-xs text-red-500/70 hover:text-red-400 transition-colors px-2 py-1 rounded shrink-0"
                        >
                            Revoke
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Generate key form --}}
        <div class="px-6 py-4 border-t border-white/[0.06]">
            <form wire:submit="generateKey" class="flex gap-3">
                <input
                    wire:model="newKeyName"
                    type="text"
                    placeholder="Key name (e.g. Home Assistant)"
                    class="flex-1 bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition"
                />
                <button
                    type="submit"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
                >
                    Generate key
                </button>
            </form>
        </div>
    </div>
</div>
