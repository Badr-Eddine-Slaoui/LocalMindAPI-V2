import { useAuthStore } from '~~/stores/auth'

export default defineNuxtRouteMiddleware((to) => {
    if (import.meta.client) {
        return
    }
    const auth = useAuthStore()

    if (!auth.isLoggedIn || auth.isTokenExpired()) return

    switch (auth.role) {
        case 'admin':
            return navigateTo('/admin', { replace: true })
        case 'user':
            return navigateTo('/', { replace: true })
    }
})
