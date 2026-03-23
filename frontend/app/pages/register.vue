<script setup lang="ts">
import type { ReturnData } from '~~/types/api';
import { useAuthStore } from '../../stores/auth';
const auth = useAuthStore()

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const errs = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    login: '',
})

const submit = async () => {
    const res: ReturnData<any> = await auth.register(form);
    if (res.success) {
        navigateTo('/login')
    }

    if (res.errors) {
        errs.value = res.errors
    }
}

definePageMeta({
    middleware: [
        'guest',
    ],
})

</script>

<template>
    <NuxtLayout>
        <main class="flex-1 flex items-center justify-center p-6 sm:p-12 relative overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-cyan-500 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-900 rounded-full blur-[120px]"></div>
            </div>
            <div class="w-full max-w-[500px] z-10">
                <div class="scroll-card charred-edge p-8 sm:p-12 rounded-2xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-primary/40"></div>
                    <div class="absolute top-0 right-0 w-8 h-8 border-t-2 border-r-2 border-primary/40"></div>
                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-2 border-l-2 border-primary/40"></div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-primary/40"></div>
                    <div class="text-center mb-8">
                        <h1 class="text-white text-3xl font-extrabold tracking-tight mb-2 uppercase italic neon-text-gold">
                            Register Your Bounty</h1>
                        <p class="text-gray-400 text-sm tracking-widest">TRANSMISSION ENCRYPTED: NEW PIRATE DETECTED</p>
                    </div>
                    <form @submit.prevent="submit" method="POST" class="space-y-5">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-gray-400 text-xs font-bold uppercase tracking-widest ml-1">Codename</label>
                            <div class="relative">
                                <input
                                    class="glow-input w-full rounded-lg h-14 pl-12 pr-4 text-white placeholder:text-gray-600 focus:ring-0"
                                    placeholder="Monkey D. Luffy" type="text" v-model="form.name" />
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary/60">shield_person</span>
                            </div>
                            <span v-if="errs.name" class="text-red-500 text-xs mt-1">{{ errs.name }}</span>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-gray-400 text-xs font-bold uppercase tracking-widest ml-1">Snail
                                Frequency
                                (Email)</label>
                            <div class="relative">
                                <input
                                    class="glow-input w-full rounded-lg h-14 pl-12 pr-4 text-white placeholder:text-gray-600 focus:ring-0"
                                    placeholder="captain@sunny.go" type="email" v-model="form.email" />
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary/60">settings_input_antenna</span>
                            </div>
                            <span v-if="errs.email" class="text-red-500 text-xs mt-1">{{ errs.email }}</span>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-gray-400 text-xs font-bold uppercase tracking-widest ml-1">Security
                                Cipher</label>
                            <div class="relative">
                                <input
                                    class="glow-input w-full rounded-lg h-14 pl-12 pr-4 text-white placeholder:text-gray-600 focus:ring-0"
                                    placeholder="••••••••" type="password" v-model="form.password" />
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary/60">lock</span>
                            </div>
                            <span v-if="errs.password" class="text-red-500 text-xs mt-1">{{ errs.password }}</span>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label class="text-gray-400 text-xs font-bold uppercase tracking-widest ml-1">Re-verify
                                Cipher</label>
                            <div class="relative">
                                <input
                                    class="glow-input w-full rounded-lg h-14 pl-12 pr-4 text-white placeholder:text-gray-600 focus:ring-0"
                                    placeholder="••••••••" type="password" v-model="form.password_confirmation" />
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-primary/60">verified</span>
                            </div>
                            <span v-if="errs.password_confirmation" class="text-red-500 text-xs mt-1">{{
                                errs.password_confirmation }}</span>
                        </div>
                        <div class="pt-4">
                            <button
                                class="crimson-btn w-full text-white font-black text-lg py-4 rounded-lg flex items-center justify-center gap-3 group uppercase tracking-widest"
                                type="submit">
                                <span class="material-symbols-outlined">directions_boat</span>
                                <span>SET SAIL</span>
                            </button>
                        </div>
                    </form>
                    <div class="mt-8 text-center border-t border-cyan-900/30 pt-6">
                        <p class="text-gray-400 text-sm">
                            Already in the crew?
                            <NuxtLink to="/login" class="text-primary font-bold hover:neon-text-blue transition-all">Return
                                to
                                Ship</NuxtLink>
                        </p>
                    </div>
                </div>
                <div class="mt-8 flex justify-center opacity-20 filter invert brightness-50 contrast-125">
                    <div class="h-16 w-full max-w-[300px] bg-center bg-no-repeat bg-contain"
                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCGL9Cc8VT2JGKneEtu3ldvLXRs00GcLgSvZCK2fJXMrukgqcEP7z1uoq4JuIOsGLni8-MJTPsTrEvaEppQjkRwdtdOo5vWRLPMd79EZ7MZbz-QT4lZ22UeZREznHn5IeCTOvSeeuK7XnuYJIlPH36Oyw7aqoclv1v67ew2TsZp7ekSK9JRfWmK1UWoHccawwycdhkgACQzX-lyfs9BvBpfGzbEkoQFjtKqdbsLnmcI3YYu7GZ4a85mhgjBzxaJbH9D-onWXjbjjg4");'>
                    </div>
                </div>
            </div>
        </main>
    </NuxtLayout>
</template>