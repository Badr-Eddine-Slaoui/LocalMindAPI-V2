<script setup lang="ts">
import { useRoute } from '#imports'
import { useAuthStore } from '~~/stores/auth';
import type { ReturnData } from '~~/types/api';

const auth = useAuthStore()

const route = useRoute()

const isActive = (r: string, deep = false) => {
    const active = deep
        ? route.path.startsWith(r)
        : route.path === r

    return active
        ? 'text-primary text-sm font-bold border-b-2 border-primary pb-1'
        : 'text-white/70 text-sm font-medium hover:text-white transition-colors'
}

const submit = async () => {
    const res: ReturnData<any> = await auth.logout();
    if (res.success) {
        navigateTo('/login');
    }
}
</script>

<template>
    <header class="sticky top-0 z-50 w-full border-b border-white/5 bg-sidebar-black">
        <div class="max-w-[1200px] mx-auto px-4 lg:px-10 py-3 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <NuxtLink to="/">
                    <div class="flex items-center gap-3 text-primary">
                        <div class="size-8">
                            <svg fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M36.7273 44C33.9891 44 31.6043 39.8386 30.3636 33.69C29.123 39.8386 26.7382 44 24 44C21.2618 44 18.877 39.8386 17.6364 33.69C16.3957 39.8386 14.0109 44 11.2727 44C7.25611 44 4 35.0457 4 24C4 12.9543 7.25611 4 11.2727 4C14.0109 4 16.3957 8.16144 17.6364 14.31C18.877 8.16144 21.2618 4 24 4C26.7382 4 29.123 8.16144 30.3636 14.31C31.6043 8.16144 33.9891 4 36.7273 4C40.7439 4 44 12.9543 44 24C44 35.0457 40.7439 44 36.7273 44Z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-white text-xl font-extrabold tracking-tight">Grand Line Q&amp;A</h2>
                    </div>
                </NuxtLink>
                <nav class="hidden md:flex items-center gap-6">
                    <NuxtLink to="/" :class="isActive('/')">Home</NuxtLink>
                    <template v-if="auth.isLoggedIn">
                        <NuxtLink to="/favorites"
                            :class="isActive('/favorites', true)">Favorites
                        </NuxtLink>
                        <template v-if="auth.role === 'admin'">
                            <NuxtLink to="/admin"
                                :class="isActive('/admin', true)">Admin
                            </NuxtLink>
                        </template>
                    </template>
                </nav>
            </div>
            <div class="flex flex-1 justify-end items-center gap-4 lg:gap-6">
                <template v-if="auth.isLoggedIn">
                    <form @submit.prevent="submit" method="POST" class="flex items-center gap-3">
                        <button
                            class="hidden lg:flex min-w-[84px] cursor-pointer items-center justify-center rounded-xl h-10 px-4 bg-white/10 hover:bg-white/20 text-white text-sm font-bold transition-all">
                            <span>Logout</span>
                        </button>
                    </form>
                </template>
                <template v-else>
                    <NuxtLink to="/login"
                        class="hidden lg:flex min-w-[84px] cursor-pointer items-center justify-center rounded-xl h-10 px-4 bg-white/10 hover:bg-white/20 text-white text-sm font-bold transition-all">
                        <span>Login</span>
                    </NuxtLink>
                    <NuxtLink to="/register"
                        class="hidden lg:flex min-w-[84px] cursor-pointer items-center justify-center rounded-xl h-10 px-4 bg-white/10 hover:bg-white/20 text-white text-sm font-bold transition-all">
                        <span>Register</span>
                    </NuxtLink>
                </template>
            </div>
        </div>
    </header>
</template>