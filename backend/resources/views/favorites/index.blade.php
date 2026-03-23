@extends('layouts.main')

@section('title', 'Favorites')

@section('content')
    <main class="flex-1 max-w-[1200px] mx-auto flex flex-col lg:flex-row gap-8 px-4 lg:px-10 py-8 w-full">
        <aside class="hidden xl:flex flex-col w-64 gap-6 shrink-0">
            <div class="bg-sidebar-black rounded-xl border border-white/5 overflow-hidden">
                <div class="bg-primary/20 h-16 w-full relative">
                    <div class="absolute -bottom-6 left-5 border-4 border-sidebar-black rounded-full bg-slate-800 size-16 bg-cover"
                        data-alt="User profile avatar"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDhBQM-VWimTx20Ff85Qh5jwbaLNgoKOH1p93TwHtkO1qM_RhSXu8O5pyfj6gJejxyxN95FflooabgKGIFcqG_ERaZh0AZzD7M04ljNR-73eMUH4Y8nu-ze8g8TRMy-X_7blhRn5wUNG23GFgE_cqgAjDm1ni87C1DPO16tvxYfFXfkcG0pihNfrbm5mfnqK7ERwgBteY0QnYfLc-hIQuz3EkMVXATBW9xWzELgTeoVVQwsqLvnT9IxUqf5YsfYGQEZ3r1KQHzAwns");'>
                    </div>
                </div>
                <div class="pt-8 px-5 pb-5">
                    <h4 class="font-bold text-white text-lg">{{ auth()->user()->name }}</h4>
                    <p class="text-primary text-[10px] font-bold uppercase tracking-wider mb-4">Adventurer Class</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-2 bg-white/5 rounded-lg border border-white/5">
                            <span
                                class="block text-primary font-bold text-lg">{{ auth()->user()->favorites->count() }}</span>
                            <span class="text-[10px] text-white/40 font-bold uppercase">Favorites</span>
                        </div>
                        <div class="text-center p-2 bg-white/5 rounded-lg border border-white/5">
                            <span
                                class="block text-primary font-bold text-lg">{{ auth()->user()->questions->count() }}</span>
                            <span class="text-[10px] text-white/40 font-bold uppercase">Questions</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-sidebar-black p-5 rounded-xl border border-white/5">
                <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">collections_bookmark</span>
                    Your Vault
                </h3>
                <p class="text-white/40 text-xs mb-4">Stored treasures from across the four seas.</p>
                <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-primary w-[{{ auth()->user()->favorites->count() }}%]"></div>
                </div>
                <p class="text-[10px] text-white/30 mt-2 font-bold uppercase">{{ auth()->user()->favorites->count() }}/100
                    Slots Filled</p>
            </div>
        </aside>
        @if (count($favorites) > 0)
            <div class="flex-1 flex flex-col">
                <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-white gold-glow-text">User Favorites Vault</h1>
                        <p class="text-white/50 mt-1">Manage your collected knowledge treasures.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($favorites as $favorite)
                        <div
                            class="bg-card-dark border border-white/5 p-6 rounded-2xl hover:border-primary/30 transition-all hover:shadow-card-hover group relative flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <span
                                        class="px-2.5 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-wider rounded border border-primary/20">{{ $favorite->question->user->name }}</span>
                                    <form action="{{ route('unfavorite', $favorite->question) }}" method="post">
                                        @csrf
                                        <button class="text-white/20 hover:text-accent-ruby transition-colors"
                                                title="Remove from Vault">
                                                <span class="material-symbols-outlined">delete_forever</span>
                                        </button>
                                    </form>
                                </div>
                                <h3
                                    class="text-white text-lg font-bold mb-3 group-hover:text-primary transition-colors leading-tight">
                                    {{ $favorite->question->title }}</h3>
                                <p class="text-white/40 text-sm line-clamp-2 mb-6">{{ $favorite->question->description }}</p>
                            </div>
                            <div class="flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1.5 text-white/60">
                                        <span class="material-symbols-outlined text-lg">chat_bubble</span>
                                        <span class="text-xs font-bold">{{ $favorite->question->answers_count }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-primary">
                                        <span class="material-symbols-outlined text-lg fill-icon">star</span>
                                        <span class="text-xs font-bold">{{ $favorite->question->favorites_count }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('questions.show', $favorite->question) }}"
                                    class="text-[10px] font-black uppercase tracking-tighter text-white/30 group-hover:text-primary transition-colors flex items-center gap-1">
                                    View Question <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div
                class="flex-1 flex flex-col items-center justify-center relative min-h-[600px] rounded-3xl overflow-hidden">
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent via-background-deep to-[#050608] pointer-events-none">
                </div>
                <div class="absolute top-10 right-10 opacity-20">
                    <span class="material-symbols-outlined text-6xl text-slate-400 rotate-12">dark_mode</span>
                </div>
                <div class="relative z-10 flex flex-col items-center text-center max-w-md px-6">
                    <div class="relative mb-12 flex flex-col items-center justify-center">
                        <div
                            class="absolute -top-10 left-1/2 -translate-x-1/2 w-48 h-48 bg-primary/5 rounded-full blur-[60px]">
                        </div>
                        <div class="absolute bottom-[-10px] w-64 h-12 bg-[#0d1117] rounded-[100%] blur-xl opacity-80"></div>
                        <div class="relative group">
                            <span
                                class="material-symbols-outlined text-[160px] text-white/10 leading-none treasure-glow">inventory_2</span>
                            <span
                                class="material-symbols-outlined absolute top-0 left-0 text-[160px] text-white/5 -translate-y-4 -rotate-3 scale-y-75">folder_open</span>
                            <span
                                class="material-symbols-outlined absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-4xl text-white/5">auto_awesome</span>
                        </div>
                    </div>
                    <h2 class="text-white text-3xl font-extrabold tracking-tight mb-3">
                        Your treasure chest is empty!
                    </h2>
                    <p class="text-white/50 text-lg mb-10 leading-relaxed">
                        Mark questions as favorites to store them in your personal vault.
                    </p>
                    <a class="inline-flex items-center justify-center gap-3 bg-primary hover:bg-primary/90 text-background-deep font-black px-10 py-4 rounded-xl text-lg shadow-[0_0_20px_rgba(251,191,36,0.2)] transition-all hover:scale-105 active:scale-95 group"
                        href="{{ route('home') }}">
                        <span class="material-symbols-outlined">explore</span>
                        Explore the Grand Line
                    </a>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-32 opacity-10">
                    <svg viewBox="0 0 1440 320" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0,224L80,213.3C160,203,320,181,480,181.3C640,181,800,203,960,213.3C1120,224,1280,224,1360,224L1440,224L1440,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z"
                            fill="#ffffff" fill-opacity="1"></path>
                    </svg>
                </div>
            </div>
        @endif
    </main>
@endsection
