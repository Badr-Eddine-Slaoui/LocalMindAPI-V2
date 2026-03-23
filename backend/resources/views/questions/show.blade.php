@extends('layouts.main')

@section('title', 'Question #'.$question->id)

@section('content')
    <main class="max-w-[960px] mx-auto py-8 px-4">
        <div class="flex flex-wrap items-center gap-2 mb-6">
            <a class="text-gray-500 text-sm font-medium hover:text-teal-accent" href="{{ route('home') }}">Home</a>
            <span class="text-gray-700 text-sm font-medium">/</span>
            <span class="text-teal-accent text-sm font-medium">Question #{{ $question->id }}</span>
        </div>
        <article class="charcoal-card rounded-2xl p-8 mb-8 shadow-2xl overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-accent/5 blur-3xl rounded-full -mr-16 -mt-16"></div>
            <div class="flex flex-col gap-6">
                <div class="flex justify-between items-start gap-6">
                    <h1 class="text-white text-4xl font-black leading-tight tracking-tight">{{ $question->title }}</h1>
                    <div
                        class="flex flex-col items-center p-3 bg-white/5 border border-white/10 rounded-xl min-w-[90px] backdrop-blur-sm">
                        <span class="text-3xl font-black text-teal-accent">{{ $question->favorites_count }}</span>
                        <span class="text-[10px] uppercase font-extrabold text-gray-500 tracking-widest">Favorites</span>
                    </div>
                </div>
                <div class="flex items-center gap-4 pb-6 border-b border-white/5">
                    <div class="size-12 rounded-full border-2 border-purple-accent/50 p-0.5">
                        <div class="w-full h-full rounded-full bg-cover" data-alt="Author profile picture"
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCVPzi9W9oYdlSLUez2qWx4KymO6BtZKkszDNMzR-VLMvypsq2EjrTdt6yEZP0hMA5ge3fkmQrlt3gzZXtnfQx3PazYfGvuFfFtKRVtrgFJoCTLN8zI4w7x399-JbjAPEjV3HLB1aphQdwd8kISbbKFqZVnRTtGc7HvQUd7rGJB_tJRel_dfnmWk6GHOK0kYrT8w85MVvBWfSBaFI0frwbRaFcYZAcJ7rFjlslMz646qLc6PjCUJzNdoBvw_4m3FqDmlqUaaUGOfhY')">
                        </div>
                    </div>
                    <div>
                        <p class="text-white font-bold text-base flex items-center gap-2">
                            {{ $question->user->name }}
                            <span
                                class="px-2.5 py-0.5 bg-yellow-500/10 text-yellow-500 text-[10px] border border-yellow-500/20 rounded-full uppercase tracking-widest font-black">Captain</span>
                        </p>
                        <p class="text-gray-500 text-xs">Asked {{ $question->created_at->diffForHumans() }} </p>
                    </div>
                    <div class="ml-auto flex gap-3">
                        @auth
                            @if ($question->user_id === auth()->user()->id)
                                <a href="{{ route('questions.edit', $question) }}"
                                    class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white/5 text-gray-300 hover:bg-white/10 transition-all border border-white/5">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                    <span class="text-xs font-bold">Edit</span>
                                </a>
                                <form action="{{ route('questions.destroy', $question) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-950/30 text-red-500 hover:bg-red-900/40 transition-all border border-red-500/10">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                        <span class="text-xs font-bold">Delete</span>
                                    </button>
                                </form>
                            @endif
                        @endauth
                        @if ($question->is_favorited)
                            <form action="{{ route('unfavorite', $question) }}" method="post">
                                @csrf
                                <button
                                    class="flex items-center gap-2 px-5 py-2 rounded-xl bg-yellow-500/10 text-yellow-500 hover:bg-yellow-500/20 transition-all active:scale-95 font-bold text-sm">
                                    <span class="material-symbols-outlined text-lg fill-icon">star</span>
                                    <span>Unfavorite</span>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('favorite', $question) }}" method="post">
                                @csrf
                                <button
                                    class="flex items-center gap-2 px-5 py-2 rounded-xl bg-teal-accent text-white shadow-lg shadow-teal-500/20 hover:scale-105 transition-all active:scale-95 font-bold text-sm">
                                    <span class="material-symbols-outlined text-lg fill-icon">star</span>
                                    <span>Favorite</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="prose prose-invert max-w-none">
                    <div class="text-white text-xl leading-relaxed font-normal">
                        {{ $question->description }}
                    </div>
                </div>
            </div>
        </article>
        <div class="flex items-center justify-between mb-8 px-2">
            <h3 class="text-2xl font-black text-white flex items-center gap-3">
                <span class="material-symbols-outlined text-teal-accent text-3xl">forum</span>
                {{ $question->answers_count }} Answers
            </h3>
            <div class="h-px flex-1 mx-6 glowing-line"></div>
        </div>
        <form id="answer-form" action="{{ route('answers.store', $question) }}" method="post" class="charcoal-card rounded-2xl border-l-4 border-l-grand-line-red p-8 shadow-2xl">
            @csrf
            @method('POST')
            <h3 class="text-2xl font-black text-white mb-8">Your Contribution to the Log</h3>
            <div class="flex flex-col gap-6">
                <div class="w-full">
                    <textarea name="answer"
                        class="w-full min-h-[220px] rounded-b-xl border-white/10 bg-black/30 text-white placeholder:text-gray-600 focus:ring-teal-accent/30 focus:border-teal-accent/50 p-6 text-lg transition-all"
                        placeholder="Share your knowledge of the seas..."></textarea>
                    @error('answer')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div id="actions" class="flex justify-between items-center">
                    <div class="flex items-center gap-2 text-gray-500">
                        <span class="material-symbols-outlined text-sm">info</span>
                        <p class="text-xs font-medium">Follow the Pirate Code. Be respectful to your crewmates.</p>
                    </div>
                    <button
                        class="submit-glow flex items-center justify-center rounded-xl h-14 bg-grand-line-red text-white gap-3 text-lg font-black px-10 hover:brightness-125 hover:scale-[1.02] active:scale-95 transition-all">
                        <span class="material-symbols-outlined fill-icon">send</span>
                        <span id="submit-text">Submit to the Captain</span>
                    </button>
                </div>
            </div>
        </form>
        <div class="mt-16 space-y-4">
            @forelse ($question->answers as $answer)
                <div data-id="{{ $answer->id }}" class="relative charcoal-card rounded-2xl p-6 transition-all hover:border-teal-accent/30 group">
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="size-8 rounded-full border border-white/10 p-0.5">
                                    <div class="w-full h-full rounded-full bg-cover" data-alt="Nami avatar"
                                        style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDWaDRfcJRrb326mr01Jg6ArbI93VnupKHyP0KV4ScFiIHBPYN9S-f9azVZADwqfvaYhejknRuQaL9gSVzJ9CaTHYHQiLlqGLePz8n4cqSKfWEQP_vN57c3MP8tOvb8-YvSJoos_5jUjXvrtLoKnylVJJHzNn5T8EAgnjBxrDwUn6C-W7HYQlAsQ_PCl1UiBZeUDhdW4ieA4YH_-Y3lD2GTL-AskMT0bbwsDQp1oETO0iPUMIt00O-c4m3kX8jFVhh-dNV1n4exZhY')">
                                    </div>
                                </div>
                                <span class="font-bold text-base text-white">{{ $answer->user->name }}</span>
                                @if ($answer->user->id == auth()->id())
                                    <span
                                    class="px-2 py-0.5 bg-blue-500/10 text-teal-accent text-[9px] rounded border border-blue-500/20 uppercase font-black">You</span>
                                @elseif ($answer->user->id == $question->user_id)
                                    <span
                                    class="px-2 py-0.5 bg-yellow-500/10 text-yellow-500 text-[9px] rounded border border-yellow-500/20 uppercase font-black">Captain</span>
                                @else
                                    <span
                                    class="px-2 py-0.5 bg-teal-accent/10 text-teal-accent text-[9px] rounded border border-teal-accent/20 uppercase font-black">Navigator</span>
                                @endif
                                <span class="text-xs text-gray-500 ml-auto">{{ $answer->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="answer text-gray-300 text-lg leading-relaxed">
                                {{ $answer->answer }}
                            </div>
                        </div>
                    </div>
                    @auth
                        @if ($answer->user_id == auth()->id())
                            <div class="absolute bottom-6 right-6 flex items-center gap-3">
                                <button onclick="editAnswer({{ $answer->id }})"
                                    class="flex items-center gap-1.5 text-primary cursor-pointer hover:brightness-125 transition-all">
                                    <span class="material-symbols-outlined text-[22px]">edit</span>
                                    <span class="text-xs font-bold">Edit</span>
                                </button>
                                <form action="{{ route('answers.destroy', $answer) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="flex items-center gap-1.5 text-red-500 cursor-pointer hover:brightness-125 transition-all">
                                        <span class="material-symbols-outlined text-[22px]">delete</span>
                                        <span class="text-xs font-bold">Delete</span>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="glowing-line opacity-50"></div>
            @empty
                <div
                    class="charcoal-card rounded-2xl p-16 mb-12 flex flex-col items-center justify-center text-center overflow-hidden relative">
                    <div class="absolute inset-0 fog-effect opacity-30"></div>
                    <div class="ghost-ship-container mb-8 relative z-10">
                        <div class="text-teal-accent/20 scale-[4] opacity-40 blur-[1px]">
                            <span class="material-symbols-outlined !text-[80px]">sailing</span>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span
                                class="material-symbols-outlined text-teal-accent/60 !text-[96px] drop-shadow-[0_0_15px_rgba(20,184,166,0.4)]">sailing</span>
                        </div>
                    </div>
                    <div class="relative z-10 max-w-md">
                        <h4 class="text-3xl font-black text-white mb-3 tracking-tight">The winds are silent...</h4>
                        <p class="text-gray-400 text-lg leading-relaxed font-medium">Be the first to share your wisdom on these
                            treacherous waters!</p>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-background-dark/80 to-transparent">
                    </div>
                </div>
            @endforelse
        </div>
    </main>
    <script>
        function editAnswer(id) {
            const answer = document.querySelector(`[data-id="${id}"] .answer`).textContent.trim();
            const form = document.querySelector(`#answer-form`);
            form.querySelector('textarea').value = answer;
            form.querySelector('input[name="_method"]').value = 'PUT';
            form.action = `{{ route('answers.update', ['answer' => ':id']) }}`.replace(':id', id);
            form.querySelector('#submit-text').textContent = 'Update answer';
            const html = `
                <button id="cancel-edit" onclick="cancelEdit()"
                    class="flex items-center justify-center rounded-xl h-14 bg-slate-800 text-white gap-3 text-lg font-black px-10 hover:brightness-125 hover:scale-[1.02] active:scale-95 transition-all">
                    <span class="material-symbols-outlined fill-icon">cancel</span>
                    <span id="submit-text">Cancel</span>
                </button>
            `
            form.querySelector('#actions').insertAdjacentHTML('beforeend', html);
            form.focus();
        }

        function cancelEdit() {
            const form = document.querySelector(`#answer-form`);
            form.querySelector('textarea').value = '';
            form.querySelector('input[name="_method"]').value = 'POST';
            form.action = `{{ route('answers.store', $question) }}`;
            form.querySelector('#submit-text').textContent = 'Submit to the Captain';
            form.querySelector('#cancel-edit').remove();
        }
    </script>
@endsection
