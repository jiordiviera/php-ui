@props([
    'code' => '',
    'language' => 'plaintext',
    'filename' => null,
    'showLineNumbers' => true,
    'highlightLines' => [],
    'maxHeight' => '400px',
    'copyable' => true,
])

@php
    $uniqueId = 'code-' . uniqid();
    $lines = explode("\n", $code);
    $lineCount = count($lines);

    // Language icons and colors
    $languageConfig = [
        'php' => ['icon' => 'P', 'color' => 'text-purple-400', 'bg' => 'bg-purple-500/20'],
        'javascript' => ['icon' => 'JS', 'color' => 'text-yellow-400', 'bg' => 'bg-yellow-500/20'],
        'typescript' => ['icon' => 'TS', 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/20'],
        'python' => ['icon' => 'PY', 'color' => 'text-green-400', 'bg' => 'bg-green-500/20'],
        'bash' => ['icon' => '$', 'color' => 'text-green-400', 'bg' => 'bg-green-500/20'],
        'html' => ['icon' => '<>', 'color' => 'text-orange-400', 'bg' => 'bg-orange-500/20'],
        'css' => ['icon' => '#', 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/20'],
        'json' => ['icon' => '{}', 'color' => 'text-yellow-400', 'bg' => 'bg-yellow-500/20'],
        'sql' => ['icon' => 'DB', 'color' => 'text-cyan-400', 'bg' => 'bg-cyan-500/20'],
        'blade' => ['icon' => 'B', 'color' => 'text-red-400', 'bg' => 'bg-red-500/20'],
    ];

    $langConfig = $languageConfig[$language] ?? ['icon' => '?', 'color' => 'text-gray-400', 'bg' => 'bg-gray-500/20'];
@endphp

<div
    x-data="codeSnippet({
        code: {{ Js::from($code) }},
        copyable: {{ $copyable ? 'true' : 'false' }}
    })"
    {{ $attributes->merge(['class' => 'code-snippet rounded-lg overflow-hidden border border-border bg-zinc-900'])
 }}
>
    {{-- Header --}}
    <div class="flex items-center justify-between px-4 py-2 bg-zinc-800/50 border-b border-border">
        <div class="flex items-center gap-3">
            {{-- Window dots --}}
            <div class="flex items-center gap-1.5">
                <span class="size-3 rounded-full bg-red-500/80"></span>
                <span class="size-3 rounded-full bg-yellow-500/80"></span>
                <span class="size-3 rounded-full bg-green-500/80"></span>
            </div>

            {{-- Filename or language --}}
            <div class="flex items-center gap-2">
                <span class="text-xs font-mono px-1.5 py-0.5 rounded {{ $langConfig['bg'] }} {{ $langConfig['color'] }}">
                    {{ $langConfig['icon'] }}
                </span>
                @if($filename)
                    <span class="text-sm text-zinc-400 font-mono">{{ $filename }}</span>
                @else
                    <span class="text-sm text-zinc-500 font-mono capitalize">{{ $language }}</span>
                @endif
            </div>
        </div>

        {{-- Copy button --}}
        @if($copyable)
            <button
                @click="copyCode"
                class="flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-md transition-all
                    text-zinc-400 hover:text-zinc-200 hover:bg-zinc-700/50
                    focus:outline-none focus:ring-2 focus:ring-primary/50"
                :class="{ 'text-green-400': copied }"
            >
                <template x-if="!copied">
                    <span class="flex items-center gap-1.5">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span>Copy</span>
                    </span>
                </template>
                <template x-if="copied">
                    <span class="flex items-center gap-1.5">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Copied!</span>
                    </span>
                </template>
            </button>
        @endif
    </div>

    {{-- Code body --}}
    <div
        class="relative overflow-hidden"
        style="max-height: {{ $maxHeight }}"
    >
        <div class="flex items-stretch min-h-full">
            {{-- Line numbers --}}
            @if($showLineNumbers)
                <div class="flex-shrink-0 py-4 px-3 text-right select-none bg-zinc-900/50 border-r border-zinc-800">
                    @foreach($lines as $index => $line)
                        <div
                            class="text-xs font-mono leading-6 {{ in_array($index + 1, $highlightLines) ? 'text-primary' : 'text-zinc-600' }}"
                        >{{ $index + 1 }}</div>
                    @endforeach
                </div>
            @endif

            {{-- Code --}}
            <pre class="flex-1 py-4 px-4 overflow-x-auto"><code
                class="text-sm font-mono leading-6 text-zinc-300"
                x-ref="codeContent"
            >@foreach($lines as $index => $line)<span class="{{ in_array($index + 1, $highlightLines) ? 'bg-primary/20 -mx-4 px-4 block' : '' }}">{{ $line }}
</span>@endforeach</code></pre>
        </div>
    </div>

    {{-- Footer with line count --}}
    <div class="px-4 py-1.5 bg-zinc-800/30 border-t border-zinc-800 flex items-center justify-between">
        <span class="text-xs text-zinc-500">{{ $lineCount }} lines</span>
        <span class="text-xs text-zinc-600 font-mono">{{ strtoupper($language) }}</span>
    </div>
</div>
