<script setup lang="ts">
    import { useAdminStore } from '~~/stores/admin';
    import { useAuthStore } from '~~/stores/auth';
import { useQuestion } from '~~/stores/question';

    const store = useAdminStore()
    const auth = useAuthStore()
    const question_store = useQuestion()

    onMounted(async() => {
        await store.fetchAdminData();
        await question_store.fetchQuestions();
        if(!auth.user) {
            await auth.fetchUser();
        }
    })

    const logout = async () => {
        await auth.logout();
        navigateTo('/login');
    }

    const deleteQuestion = async (id: number) => {
        await question_store.deleteQuestion(id);
    }

    definePageMeta({
        middleware: ["auth", "admin"],
    });
</script>

<template>
    <NuxtLayout name="admin">
        <div class="flex min-h-screen">
            <aside class="w-64 bg-[#070b14] border-r border-white/10 flex flex-col justify-between fixed h-full z-20">
                <div class="flex flex-col gap-6 p-4">
                    <div class="flex items-center gap-3 px-2 py-4">
                        <div
                            class="bg-admin-primary/20 size-10 rounded-lg flex items-center justify-center text-admin-primary shadow-[0_0_15px_rgba(0,242,255,0.3)]">
                            <span class="material-symbols-outlined ">anchor</span>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-white text-base font-bold leading-normal tracking-wide">Marine HQ</h1>
                            <p class="text-admin-primary/70 text-[10px] font-bold uppercase tracking-widest">Night Watch</p>
                        </div>
                    </div>
                    <nav class="flex flex-col gap-1">
                        <NuxtLink to="/admin" class="flex items-center gap-3 px-3 py-2 rounded-xl bg-admin-primary/10 text-admin-primary border border-admin-primary/20 shadow-[0_0_10px_rgba(0,242,255,0.1)] transition-colors">
                            <span class="material-symbols-outlined "
                                style="font-variation-settings: 'FILL' 1">description</span>
                            <p class="text-sm font-bold leading-normal">Log Entries</p>
                        </NuxtLink>
                        <NuxtLink to="/" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-white/5 transition-colors group">
                            <span class="material-symbols-outlined text-admin-primary/60 group-hover:text-admin-primary ">home</span>
                            <p class="text-slate-400 group-hover:text-white text-sm font-medium leading-normal">Home</p>
                        </NuxtLink>
                    </nav>
                </div>
            </aside>
            <main class="flex-1 ml-64 flex flex-col min-h-screen bg-[var(--steel-blue-bg)]">
                <header
                    class="flex items-center justify-between bg-[#070b14] border-b border-white/10 px-8 py-4 sticky top-0 z-10">
                    <div class="flex items-center gap-8">
                        <div class="flex items-center gap-4 text-white">
                            <div class="size-6 text-admin-primary ">
                                <span class="material-symbols-outlined">gavel</span>
                            </div>
                            <h2 class="text-white text-lg font-bold leading-tight tracking-[-0.015em]">HQ Post Management
                            </h2>
                        </div>
                    </div>
                    <div class="flex flex-1 justify-end gap-6 items-center">
                        <div class="flex gap-2">
                            <form @submit.prevent="logout" method="post">
                                <button
                                    class="flex items-center justify-center py-2 px-4 rounded-xl text-sm bg-white/5 text-red-500/80 hover:text-red-500 border border-red-500/10 hover:border-red-500/30 transition-all">
                                    <span class="material-symbols-outlined ">logout</span>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </header>
                <div class="px-8 py-4">
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="material-symbols-outlined text-slate-600 text-sm">chevron_right</span>
                        <span class="text-admin-primary text-sm font-bold ">Post Management</span>
                    </div>
                </div>
                <div class="px-8 pb-10 flex flex-col gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex flex-col gap-2 rounded-xl p-6 border border-white/10 bg-[#070b14]">
                            <div class="flex justify-between items-start">
                                <p class="text-slate-400 text-sm font-medium">Total Bounty Posts</p>
                                <span class="material-symbols-outlined text-admin-primary ">inventory_2</span>
                            </div>
                            <p class="text-white tracking-light text-2xl font-bold">{{ question_store?.questions?.length ?? 0 }}</p>
                            <p class="text-emerald-400 text-sm font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">trending_up</span> +5.2%
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 rounded-xl p-6 border border-white/10 bg-[#070b14]">
                            <div class="flex justify-between items-start">
                                <p class="text-slate-400 text-sm font-medium">Active Sailors</p>
                                <span class="material-symbols-outlined text-admin-primary ">groups</span>
                            </div>
                            <p class="text-white tracking-light text-2xl font-bold">{{ store?.users_count ?? 0 }}</p>
                            <p class="text-emerald-400 text-sm font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">trending_up</span> +2.1%
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4">
                        <h2 class="text-white text-[22px] font-bold tracking-tight">Post Moderation Log</h2>
                    </div>
                    <div v-if="question_store?.questions?.length as number > 0" class="overflow-hidden rounded-xl border border-white/10 bg-white shadow-2xl">
                        <table class="w-full text-left border-collapse table-fixed">
                            <thead>
                                <tr class="bg-slate-100 border-b-2 border-slate-200">
                                    <th class="px-6 py-5 text-xs font-black text-slate-800 uppercase tracking-widest w-32">
                                        Post ID</th>
                                    <th class="px-6 py-5 text-xs font-black text-slate-800 uppercase tracking-widest">Title
                                    </th>
                                    <th class="px-6 py-5 text-xs font-black text-slate-800 uppercase tracking-widest w-48">
                                        Author</th>
                                    <th class="px-6 py-5 text-xs font-black text-slate-800 uppercase tracking-widest w-40">
                                        Created Date</th>
                                    <th class="px-6 py-5 text-xs font-black text-slate-800 uppercase tracking-widest w-28">
                                        Answers</th>
                                    <th
                                        class="px-6 py-5 text-xs font-black text-slate-800 uppercase tracking-widest w-44 text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-900">
                                <tr v-for="question in question_store?.questions"
                                    class="bg-[var(--marine-row-blue)] border-b border-white/5 hover:brightness-125 transition-all">
                                    <td class="px-6 py-4 text-sm font-mono text-admin-primary font-bold">
                                        #QM-{{ question?.id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <p class="text-sm font-bold text-white line-clamp-1">{{ question?.title }}
                                            </p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="size-8 rounded-full bg-center bg-cover border border-admin-primary/20"
                                                style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuD-pFoh1LUtijqcvoANz-q-AFk2CGSJ06Rlk3wqDcUmIwp7LP3pS5bL448T2uqh2avgsFTnxqCaZL5mSsHUZL93TnyXwucGeMhCz-KSL9kr9lRmRcSKScj_5Phfb8zIOUJdn116ozeKoQtbj-uBh8ikaOCLgUizqo9h4NLys0FSl6NXVCDfOmxL-ZOgnnvjqdcUw9r5--STZ1j5htqaz62DDdPBv_qPT8V5D-dYk7FtHRGLao8C4SpDVNeIXrfXWqYyI04d2iM3Z20");'>
                                            </div>
                                            <span
                                                class="text-sm font-medium text-white">{{ question?.user?.name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ question?.created_at }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-admin-primary/20 text-admin-primary text-xs font-black px-3 py-1 rounded-full border border-admin-primary/30">{{ question?.answers_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <form @submit.prevent="deleteQuestion(question?.id)" method="post">
                                            <button
                                                class="buster-call-btn inline-flex items-center gap-2 text-[var(--buster-red)] bg-[var(--buster-red)]/10 px-4 py-2 rounded-lg transition-all font-black text-[10px] uppercase tracking-tighter">
                                                <span class="material-symbols-outlined text-sm">emergency_home</span>
                                                Buster
                                                Call
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else
                        class="flex flex-col items-center justify-center py-20 px-6 rounded-2xl border border-white/5 bg-[#070b14]/50 moon-glow relative overflow-hidden min-h-[500px]">
                        <div class="absolute inset-0 opacity-10 pointer-events-none">
                            <div class="absolute top-10 right-10 size-40 rounded-full bg-white/20 blur-3xl"></div>
                            <div class="absolute bottom-10 left-10 size-60 rounded-full bg-admin-primary/10 blur-3xl"></div>
                        </div>
                        <div class="relative flex flex-col items-center text-center max-w-md z-10">
                            <div class="mb-10 peace-glow">
                                <div class="relative inline-block">
                                    <svg class="w-64 h-64 text-slate-800" viewBox="0 0 200 200"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20,180 L180,180 L180,160 L160,160 L160,100 L140,80 L140,40 L60,40 L60,80 L40,100 L40,160 L20,160 Z"
                                            fill="currentColor"></path>
                                        <rect fill="#1e293b" height="20" width="20" x="90" y="40"></rect>
                                        <path d="M70,180 L70,140 L130,140 L130,180" fill="#0f172a"></path>
                                        <path d="M100,60 L85,75 L115,75 Z" fill="#00f2ff" opacity="0.3"></path>
                                        <g transform="translate(135, 145)">
                                            <circle cx="10" cy="10" fill="#0f172a" r="15" stroke="#1e293b"
                                                stroke-width="2"></circle>
                                            <text fill="#00f2ff"
                                                style="font-size: 14px; font-weight: bold; font-family: 'Plus Jakarta Sans'"
                                                x="5" y="15">Z</text>
                                            <text fill="#00f2ff" opacity="0.6"
                                                style="font-size: 10px; font-weight: bold; font-family: 'Plus Jakarta Sans'"
                                                x="12" y="8">z</text>
                                            <text fill="#00f2ff" opacity="0.4"
                                                style="font-size: 8px; font-weight: bold; font-family: 'Plus Jakarta Sans'"
                                                x="18" y="2">z</text>
                                        </g>
                                    </svg>
                                    <div
                                        class="absolute -right-4 bottom-12 bg-white/5 border border-white/20 px-4 py-2 rounded-lg backdrop-blur-md rotate-6 shadow-xl">
                                        <p
                                            class="text-[10px] text-admin-primary font-black uppercase tracking-widest text-center leading-none">
                                            Status</p>
                                        <p class="text-sm text-white font-bold whitespace-nowrap">No Crimes Reported</p>
                                    </div>
                                    <span
                                        class="material-symbols-outlined absolute -top-4 -left-4 text-slate-400 text-4xl opacity-50">dark_mode</span>
                                </div>
                            </div>
                            <h3 class="text-white text-2xl font-bold mb-3">The Grand Line is at peace...</h3>
                            <p class="text-slate-400 text-base leading-relaxed mb-8">No pending questions or reports for
                                the Fleet Commander to review. Even the Sea Kings are quiet tonight.</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </NuxtLayout>
</template>