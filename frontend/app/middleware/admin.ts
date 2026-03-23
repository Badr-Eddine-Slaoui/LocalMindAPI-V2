import { useAuthStore } from '~~/stores/auth';


export default defineNuxtRouteMiddleware((to, from) => {
    if (import.meta.client) {
        return
    }
    const auth = useAuthStore()
    
    if (!auth.role || auth.role !== 'admin') {
        
        if (auth.role === 'user') {
            return navigateTo('/user', { replace: true })
        }

        return navigateTo('/login', { replace: true })
    }
})