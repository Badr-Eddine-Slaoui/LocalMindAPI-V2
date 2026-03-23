<script setup lang="ts">
    import { useAuthStore } from '~~/stores/auth';
    import { useQuestion } from '~~/stores/question';
    import { useFavorite } from '../../stores/favorite';

    useHead({
        title: `Home - Ask Anything`
    })

    const store = useQuestion();
    const auth = useAuthStore();
    const favorite_store = useFavorite();

    onMounted(async() => {
        await store.fetchQuestions();
        if(!auth.user) {
            await auth.fetchUser();
        }
    })

    const deleteQuestion = async (id: number) => {
        await store.deleteQuestion(id);
    }
</script>

<template>
    <NuxtLayout>
        <main class="max-w-[1200px] mx-auto flex flex-col lg:flex-row gap-8 px-4 lg:px-10 py-8">
            <aside class="hidden lg:flex flex-col w-64 gap-6 shrink-0">
                <NuxtLink to="/questions/create"
                    class="w-full bg-primary hover:bg-primary/90 text-background-deep rounded-xl py-4 px-6 font-bold shadow-lg shadow-primary/10 flex items-center justify-center gap-2 transition-transform active:scale-95">
                    <span class="material-symbols-outlined">edit_square</span>
                    Ask Question
                </NuxtLink>
                <div class="bg-sidebar-black p-5 rounded-xl border border-white/5">
                    <h3 class="font-bold text-white mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">trending_up</span>
                        Trending Topics
                    </h3>
                    <ul class="space-y-3">
                        <li><a class="text-white/60 hover:text-primary text-sm flex justify-between group" href="#"><span
                                    class="group-hover:translate-x-1 transition-transform">#DevilFruit</span> <span
                                    class="text-white/30">1.2k</span></a></li>
                        <li><a class="text-white/60 hover:text-primary text-sm flex justify-between group" href="#"><span
                                    class="group-hover:translate-x-1 transition-transform">#GrandLine</span> <span
                                    class="text-white/30">850</span></a></li>
                        <li><a class="text-white/60 hover:text-primary text-sm flex justify-between group" href="#"><span
                                    class="group-hover:translate-x-1 transition-transform">#Wanted</span>
                                <span class="text-white/30">430</span></a></li>
                        <li><a class="text-white/60 hover:text-primary text-sm flex justify-between group" href="#"><span
                                    class="group-hover:translate-x-1 transition-transform">#HakiTraining</span> <span
                                    class="text-white/30">210</span></a></li>
                    </ul>
                </div>
            </aside>
            <div class="flex-1 max-w-[800px]">
                <div class="mb-6 px-2">
                    <h1 class="text-white text-3xl font-extrabold tracking-tight">Questions Feed</h1>
                    <p class="text-white/40 mt-1">Navigate the sea of knowledge</p>
                </div>
                <div class="mb-6 border-b border-white/5">
                    <div class="flex gap-8 overflow-x-auto no-scrollbar">
                        <NuxtLink to="/" class="flex flex-col items-center justify-center border-b-[3px] border-primary text-primary pb-3 transition-colors">
                            <p class="text-sm font-bold whitespace-nowrap">All Questions</p>
                        </NuxtLink>
                    </div>
                </div>
                <div class="space-y-5">
                    <template v-if="store.questions?.length as number > 0">
                        <div v-for="question in store.questions" :key="question.id"
                            class="relative question-card group bg-card-dark rounded-xl overflow-hidden shadow-lg transition-all duration-300 border border-white/5">
                            <template v-if="auth.isLoggedIn">
                                <template v-if="auth.user?.id === question?.user?.id">
                                    <NuxtLink :to="`/questions/edit/${question?.id}`"
                                        class="absolute top-4 right-4 text-white/30 hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined">edit</span>
                                    </NuxtLink>
                                    <form @submit.prevent="deleteQuestion(question?.id)" method="post">
                                        <button type="submit"
                                            class="absolute top-4 right-12 text-white/30 hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </template>
                            </template>
                            <div class="flex flex-col md:flex-row">
                                <div class="flex-1 p-5 md:p-6 flex flex-col justify-between">
                                    <div>
                                        <h3
                                            class="text-white text-xl font-bold leading-tight group-hover:text-primary transition-colors">
                                            {{ question?.title }}</h3>
                                        <p class="text-white/60 mt-2 text-base line-clamp-2">
                                            {{ question?.description }}
                                        </p>
                                    </div>
                                    <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="flex items-center gap-1.5 text-primary cursor-pointer hover:brightness-125 transition-all">
                                                <span class="material-symbols-outlined text-[22px]">chat_bubble</span>
                                                <span class="text-sm font-bold">{{ question?.answers_count }}</span>
                                            </div>
                                            <div
                                                class="flex items-center gap-1.5 text-primary cursor-pointer hover:brightness-125 transition-all">
                                                <span class="material-symbols-outlined text-[22px]">star</span>
                                                <span class="text-sm font-bold">{{ question?.favorites_count }}</span>
                                            </div>
                                            <form v-if="question?.is_favorited" @submit.prevent="favorite_store.unfavorite(question?.id)" method="POST">
                                                <button
                                                    class="flex items-center gap-1.5 text-accent-ruby cursor-pointer hover:scale-110 transition-transform">
                                                    <span
                                                        class="material-symbols-outlined fill-icon text-[22px]">favorite</span>
                                                </button>
                                            </form>
                                            <form v-else @submit.prevent="favorite_store.favorite(question?.id)" method="POST">
                                                <button
                                                    class="flex items-center gap-1.5 text-primary cursor-pointer hover:brightness-125 transition-all">
                                                    <span class="material-symbols-outlined text-[22px]">favorite</span>
                                                </button>
                                            </form>
                                        </div>
                                        <NuxtLink :to="`/questions/${question?.id}`"
                                            class="bg-white/5 hover:bg-primary hover:text-background-deep text-primary border border-primary/20 px-5 py-2 rounded-lg text-sm font-bold transition-all">
                                            Read More
                                        </NuxtLink>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div
                            class="flex-1 max-w-[800px] flex flex-col items-center justify-center min-h-[600px] moonlight-gradient rounded-3xl border border-white/5">
                            <div class="relative w-full max-w-md aspect-video flex items-center justify-center mb-8">
                                <div class="absolute inset-0 flex items-center justify-center opacity-20 pointer-events-none">
                                    <div class="w-64 h-1 bg-primary/40 blur-xl rounded-full translate-y-16"></div>
                                </div>
                                <div class="relative z-10 flex flex-col items-center">
                                    <div
                                        class="w-32 h-40 bg-card-dark rounded-[2rem] border-4 border-white/10 relative overflow-hidden flex items-center justify-center shadow-2xl">
                                        <div class="absolute inset-x-0 top-1/4 h-1 bg-white/5"></div>
                                        <div class="absolute inset-x-0 top-2/4 h-1 bg-white/5"></div>
                                        <div class="absolute inset-x-0 top-3/4 h-1 bg-white/5"></div>
                                        <span class="material-symbols-outlined text-white/20 text-6xl">inventory_2</span>
                                    </div>
                                    <div class="mt-4 w-40 h-2 bg-primary/20 blur-md rounded-full"></div>
                                </div>
                            </div>
                            <div class="text-center px-6">
                                <h2 class="text-white text-3xl font-extrabold tracking-tight mb-3">The sea is quiet... too quiet.</h2>
                                <p class="text-white/40 text-lg mb-10 max-w-md mx-auto">No questions found in this sector of the Grand
                                    Line.</p>
                                <NuxtLink to="/questions/create"
                                    class="bg-primary hover:bg-primary/90 text-background-deep px-8 py-4 rounded-xl font-bold text-lg shadow-xl shadow-primary/10 transition-all hover:scale-105 active:scale-95 flex items-center gap-3 mx-auto">
                                    <span class="material-symbols-outlined">add_circle</span>
                                    Ask the First Question
                                </NuxtLink>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <template v-if="auth.isLoggedIn">
                <aside class="hidden xl:flex flex-col w-64 gap-6 shrink-0">
                    <div class="bg-sidebar-black rounded-xl border border-white/5 overflow-hidden">
                        <div class="bg-primary/20 h-16 w-full relative">
                            <div class="absolute -bottom-6 left-5 border-4 border-sidebar-black rounded-full bg-slate-800 size-16 bg-cover"
                                data-alt="User profile avatar"
                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDR0evWZX-vKJhOa9xL7KyRAQj2PTRGVVrxxu9qRzibNwn8K1hcoPb6gDS3Q_6NecjQODTCIj2q8Bfy10kL5ycghjBKUh__oX7N-L7BPp7Yt6VUsEm1NVMho1jwcdIZIKD5aIs3MGWuPCJvb0pALX5xNj0Ov8HCRFunhq-xhnZDL6wMPVw3oRK6EQzpDYy17y58Tf-c0T3FAxXA9PqIXPD6CWqK64pmfLJojmmKdFhcPiRWUowkU7cZMg88wiZjjbxEvRybUQYYbo0");'>
                            </div>
                        </div>
                        <div class="pt-8 px-5 pb-5">
                            <h4 class="font-bold text-white text-lg">{{ auth.user?.name }}</h4>
                            <p class="text-primary text-[10px] font-bold uppercase tracking-wider mb-4">Adventurer Class</p>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-2 bg-white/5 rounded-lg border border-white/5">
                                    <span class="block text-primary font-bold text-lg">{{ auth.user?.favorites?.length }}</span>
                                    <span class="text-[10px] text-white/40 font-bold uppercase">Favorites</span>
                                </div>
                                <div class="text-center p-2 bg-white/5 rounded-lg border border-white/5">
                                    <span class="block text-primary font-bold text-lg">{{ auth.user?.questions?.length }}</span>
                                    <span class="text-[10px] text-white/40 font-bold uppercase">Questions</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <template v-if="auth.role === 'admin'">
                        <div class="bg-sidebar-black p-5 rounded-xl border border-white/5">
                            <h3 class="font-bold text-white mb-3 text-sm flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-lg">policy</span>
                                Admin Insights
                            </h3>
                            <p class="text-white/40 text-xs mb-4">Moderation tools for current sea sector.</p>
                            <NuxtLink to="/admin" class="inline-flex items-center gap-2 text-primary font-bold text-sm hover:underline">
                                Moderator Dashboard
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </NuxtLink>
                        </div>
                    </template>
                </aside>
            </template>
        </main>
        <button
            class="lg:hidden fixed bottom-6 right-6 size-14 bg-primary text-background-deep rounded-full shadow-2xl flex items-center justify-center active:scale-90 transition-transform z-40">
            <span class="material-symbols-outlined">add</span>
        </button>
    </NuxtLayout>
</template>