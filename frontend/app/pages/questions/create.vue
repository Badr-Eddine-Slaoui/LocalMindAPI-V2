<script setup lang="ts">
    import { useAuthStore } from '~~/stores/auth';
    import { useQuestion } from '~~/stores/question';
    import type { ReturnData } from '~~/types/api';

    const store = useQuestion();
    const auth = useAuthStore();

    const form = reactive({
        title: '',
        description: ''
    })

    const errs = ref({
        title: '',
        description: ''
    })

    const submit = async() => {
        const res: ReturnData<any> = await store.createQuestion(form);

        if(res.success) {
            navigateTo('/')
        }
        
        if(res.errors) {
            errs.value = res.errors
        }
    }

    onMounted(async() => {
        if(!auth.user) {
            await auth.fetchUser();
        }
    })

    definePageMeta({
        middleware: ["auth"],
    });
</script>

<template>
    <NuxtLayout>
        <main class="flex-1 max-w-5xl mx-auto w-full p-6 sm:p-12 z-10">
            <div class="mb-8">
                <h1 class="text-4xl font-extrabold text-white mb-2 neon-text-gold uppercase italic">Post a New Bounty
                    Question</h1>
                <p class="text-gray-400 tracking-wide">Signal the crew. Share your knowledge or seek the truth of the Void
                    Century.</p>
            </div>
            <div class="bounty-card rounded-2xl p-8 shadow-2xl">
                <form @submit.prevent="submit" method="POST" class="space-y-8">
                    <div class="space-y-3">
                        <label class="text-primary font-bold uppercase tracking-widest text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">priority_high</span>
                            Bounty Subject (Title)
                        </label>
                        <input v-model="form.title"
                            class="w-full bg-transparent border-b-2 border-gray-700 focus:border-primary text-white text-2xl font-bold py-3 outline-none transition-colors placeholder:text-gray-700"
                            placeholder="What is the secret of the Poneglyphs?" type="text" />
                        <span v-if="errs.title" class="text-red-500 text-xs mt-1">{{ errs.title }}</span>
                    </div>
                    <div class="space-y-3">
                        <label class="text-primary font-bold uppercase tracking-widest text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">description</span>
                            Log Details (Description)
                        </label>
                        <div class="editor-bg rounded-xl overflow-hidden border border-cyan-900/30">
                            <textarea v-model="form.description"
                                class="w-full min-h-[300px] bg-transparent p-6 text-gray-200 resize-none focus:ring-0 border-none outline-none leading-relaxed"
                                placeholder="Describe your query in detail for the crew members..."></textarea>
                        </div>
                        <span v-if="errs.description" class="text-red-500 text-xs mt-1">{{ errs.description }}</span>
                    </div>
                    <div class="pt-6 flex flex-col items-center">
                        <button
                            class="crimson-btn w-full md:w-2/3 py-5 rounded-xl text-white font-black text-xl flex items-center justify-center gap-4 uppercase tracking-[0.2em]"
                            type="submit">
                            <span class="material-symbols-outlined text-3xl">edit_note</span>
                            Post to the Log
                        </button>
                        <p class="mt-4 text-gray-600 text-xs font-medium uppercase tracking-tighter">Encrypted Transmission
                            via Transponder Snail</p>
                    </div>
                </form>
            </div>
        </main>
    </NuxtLayout>
</template>