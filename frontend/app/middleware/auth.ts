import { useAuthStore } from '~~/stores/auth';

export default defineNuxtRouteMiddleware(async (to, from) => {
    if (import.meta.client) {
        return
    }
    const auth = useAuthStore()
    
    if (!auth.isLoggedIn || auth.isTokenExpired()) {
        return navigateTo('/login', { replace: true })
    }

    return 
})