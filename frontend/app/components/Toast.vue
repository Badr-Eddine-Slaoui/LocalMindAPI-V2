<script setup lang="ts">
import { useToastStore } from '../../stores/toast';
const toastStore = useToastStore()
</script>

<template>
    <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-4 max-w-sm w-full">
        <div v-for="toast in toastStore.toasts" :key="toast.id"
            :class="{ 'bg-toast-bg dark:bg-[#1e2e22] rounded-lg border border-[#ebf0ec] dark:border-[#2d3a30] overflow-hidden toast-shadow flex flex-col relative group': toast.type === 'success', 'bg-toast-bg dark:bg-[#2a1b1b] rounded-lg border border-red-100 dark:border-red-900 overflow-hidden toast-shadow flex flex-col relative group': toast.type === 'error' }">
            <div class="p-4 flex gap-3 items-center">
                <div v-if="toast.type === 'success'" class="flex-shrink-0">
                    <span class="material-symbols-outlined text-[#428a50] text-2xl font-bold">check_circle</span>
                </div>
                <div v-if="toast.type === 'error'" class="flex-shrink-0">
                    <span class="material-symbols-outlined text-error text-2xl font-bold">error</span>
                </div>
                <div class="flex-1">
                    <h4 class="text-[#121713] dark:text-white text-sm font-bold leading-tight">{{ toast.message }}</h4>
                </div>
                <button @click="toastStore.remove(toast.id)"
                    class="text-[#638369] hover:text-[#121713] dark:hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
            <div :class="[
                'h-[3px] w-full overflow-hidden',
                toast.type === 'success'
                    ? 'bg-[#ebf0ec] dark:bg-primary/20'
                    : 'bg-red-100 dark:bg-error/20'
            ]">
                <div class="h-full animate-progress" :class="toast.type === 'success' ? 'bg-primary' : 'bg-error'">
                </div>
            </div>
        </div>
    </div>
</template>