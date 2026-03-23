@extends('layouts.main')

@section('title', 'Login')

@section('content')
    <main class="flex-1 flex items-center justify-center p-6 z-10">
        <div class="w-full max-w-[480px] flex flex-col gap-2">
            <div class="text-center mb-6">
                <h1 class="text-white tracking-tight text-[36px] font-extrabold leading-tight pb-2">Welcome Back,
                    Nakama!</h1>
                <p class="text-slate-400 text-base font-normal leading-normal">The stars guide your path to wisdom.
                </p>
            </div>
            <div class="glass-card rounded-2xl shadow-2xl overflow-hidden p-10">
                <form action="{{ route('submitLogin') }}" method="POST" class="flex flex-col gap-6">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label class="text-silver-mist text-sm font-semibold leading-normal">Email Address</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">alternate_email</span>
                            <input
                                class="w-full pl-12 pr-4 h-14 rounded-lg border border-white/10 bg-white/5 text-white focus:ring-2 focus:ring-pirate-gold/50 focus:border-pirate-gold transition-all outline-none placeholder:text-slate-600"
                                placeholder="luffy@thousandsunny.com" type="email" name="email" value="{{ old('email') }}" />
                        </div>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <label class="text-silver-mist text-sm font-semibold leading-normal">Secret Code
                                (Password)</label>
                        </div>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">lock</span>
                            <input
                                class="w-full pl-12 pr-4 h-14 rounded-lg border border-white/10 bg-white/5 text-white focus:ring-2 focus:ring-pirate-gold/50 focus:border-pirate-gold transition-all outline-none placeholder:text-slate-600"
                                placeholder="••••••••" type="password" name="password" value="{{ old('password') }}" />
                        </div>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input name="remember" @checked(old('remember'))
                                    class="peer h-5 w-5 rounded border-white/20 bg-white/5 text-pirate-gold focus:ring-pirate-gold focus:ring-offset-obsidian"
                                    type="checkbox" />
                                <span
                                    class="material-symbols-outlined absolute -right-8 text-slate-500 text-lg peer-checked:text-pirate-gold transition-colors">anchor</span>
                            </div>
                            <span class="text-sm text-slate-400 ml-6 group-hover:text-silver-mist transition-colors">Stay
                                aboard for 30 days</span>
                        </label>
                    </div>
                    @if (session('error'))
                        <div class="text-sm text-rose-500">
                            {{ session('error') }}
                        </div>
                    @endif
                    <button
                        class="w-full h-14 bg-straw-red hover:bg-[#ff4d5d] active:scale-[0.98] text-white font-bold text-lg rounded-lg shadow-lg shadow-straw-red/20 transition-all flex items-center justify-center gap-2"
                        type="submit">
                        <span>Login</span>
                        <span class="material-symbols-outlined">directions_boat</span>
                    </button>
                </form>
                <div class="relative my-8 text-center">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <span class="relative px-4 bg-[#0d141d] text-[10px] text-slate-500 uppercase tracking-[0.2em]">Set
                        sail with</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button
                        class="flex items-center justify-center gap-2 h-12 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all">
                        <div class="w-5 h-5 bg-center bg-contain bg-no-repeat"
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB_o-ukkpICbT4KYX4QiFMjsi4b1NMX0z4fLGLZnPfgUlR_ezzLaW0vIxQDIU0RCivoGbF71NmrGtcSTTaGGo0S0tjbZJu60oE3fQRAJfvKGhgCQjEBQkQNpHACFqqLDJnhGv69lNifu4U6TLGpKOvF3-yjJ5zOGwM-f5_shtgXDzkzEkjOaVc53PGlcVohm8f_vBeltFTrRkSuWjQtPTrItsiJ4qiwT1ngGGP9kfayWRNuwbs_G6NeI6LhfMGbT9a8B3oSzuS5Knw')">
                        </div>
                        <span class="text-sm font-medium text-silver-mist">Google</span>
                    </button>
                    <button
                        class="flex items-center justify-center gap-2 h-12 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all">
                        <div class="w-5 h-5 bg-center bg-contain bg-no-repeat"
                            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBYsh5eTWHjx6Y9ymgDHnEixSdCW8A5RFRSHHTyR-P6zq1f-a7xMIQAUdM6hQzMVxn8HZiLmgB0KCjjfGzGmtGlZJ_Afxohsx0djvElSIUlyUHAQLndjuzWXFOa1CH0WlfQODJ_ukzEEvAQdI3bfsqn73Ygafy8sr9J9b1TCQwuHvHZb1kxGGTx_UgJp0ezzMfuEA_1hZDUFr3n6fPUMaJrKEuqujovg3glhzBrIcreME3WThzfDqp7XvQsAWg0qAb6DGPVxPDNT0Q')">
                        </div>
                        <span class="text-sm font-medium text-silver-mist">Discord</span>
                    </button>
                </div>
            </div>
            <div class="mt-8 flex flex-col items-center gap-4">
                <p class="text-sm text-slate-500">
                    Don't have a bounty yet?
                    <a class="text-pirate-gold font-bold hover:underline ml-1" href="{{ route('register') }}">Join the Crew</a>
                </p>
                <div class="flex items-center gap-6 text-xs text-slate-600">
                    <a class="hover:text-silver-mist transition-colors" href="#">Pirate Code</a>
                    <span class="text-white/10">•</span>
                    <a class="hover:text-silver-mist transition-colors" href="#">Ship's Logs</a>
                    <span class="text-white/10">•</span>
                    <a class="hover:text-silver-mist transition-colors" href="#">Support</a>
                </div>
            </div>
        </div>
    </main>
@endsection
