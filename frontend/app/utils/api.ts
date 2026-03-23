// frontend/app/utils/api.ts
import { useAuthStore } from '~~/stores/auth'
import { useToastStore } from '~~/stores/toast'

export const useApi = () => {
    const config = useRuntimeConfig()
    const auth = useAuthStore()
    const toast = useToastStore()

    return $fetch.create({
        baseURL: config.public.apiBase,

        onRequest({ options }) {
            options.headers = new Headers(options.headers)
            options.headers.set('Accept', 'application/json')
            options.headers.set('Content-Type', 'application/json')

            if (auth.token) {
                options.headers.set('Authorization', `Bearer ${auth.token}`)
            }
        },

        async onResponseError({ response }) {
            if (response.status === 401) {
                if (auth.token) {
                    auth.token = null
                    auth.user = null

                    toast.push({
                        type: 'error',
                        message: 'Session expired, please login again',
                    })
                }
            }
        }
    })
}