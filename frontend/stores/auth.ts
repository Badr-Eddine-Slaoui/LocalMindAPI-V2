import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import type { AuthResponse, ReturnData } from '~~/types/api';
import type { User } from '~~/types/user';
import { useToastStore } from './toast';

export const useAuthStore = defineStore(
    'auth',
    () => {
        const api = useApi()
        
        const toast = useToastStore()

        const user = ref<User | null>(null)
        const token = ref<string | null>(null)

        const isLoggedIn = computed(() => !!token.value)

        const decoded = computed(() =>
            token.value ? decodeJwt(token.value as string) : null
        )

        const role = computed(() => decoded.value?.role ?? null)

        function isTokenExpired() {
            if (!decoded.value) return true
            if (!decoded.value.exp) return true
            return decoded.value.exp * 1000 < Date.now()
        }

        async function register(data: { name: string; email: string; password: string; password_confirmation: string }) {
            try {
                await api('/register', {
                    method: 'POST',
                    body: data,
                })

                toast.push({
                    type: 'success',
                    message: 'You are now registered',
                })

                return {
                    success: true
                }
            } catch (err: any) {
                if (err?.data?.errors) {
                    if (err.data.errors.name || err.data.errors.email || err.data.errors.password || err.data.errors.password_confirmation) {
                        let { name, email, password, password_confirmation } = err.data.errors;
                        name = name ? name[0] : "";
                        email = email ? email[0] : "";
                        password = password ? password[0] : "";
                        password_confirmation = password_confirmation ? password_confirmation[0] : "";
                        return {
                            success: false,
                            errors: {
                                name,
                                email,
                                password,
                                password_confirmation
                            },
                        }
                    }
                }

                if(err?.data?.message) {

                    toast.push({
                        type: 'error',
                        message: err.data.message,
                    })

                    return {
                        success: false,
                        errors: {
                            name: '',
                            email: '',
                            password: '',
                            password_confirmation: '',
                            register: err.data.message ?? '',
                        },
                    }
                }

                toast.push({
                    type: 'error',
                    message: 'Something went wrong',
                })

                return {
                    success: false,
                    errors: null,
                }
            }
        }

        async function login(data: { email: string; password: string; remember: boolean }): Promise<ReturnData<any>> {
            try {
                const res = await api<AuthResponse>('/login', {
                    method: 'POST',
                    body: data,
                })

                token.value = res.access_token
                user.value = res.user

                toast.push({
                    type: 'success',
                    message: 'You are now logged in',
                })

                return {
                    success: true,
                    data: token.value,
                }
            } catch (err: any) {
                if (err?.data?.errors) {
                    if (err.data.errors.email || err.data.errors.password) {
                        let { email, password } = err.data.errors;
                        email = email ? email[0] : "";
                        password = password ? password[0] : "";
                        return {
                            success: false,
                            errors: {
                                email,
                                password,
                                login: ''
                            },
                        }
                    }
                }

                if(err?.data?.message) {
                    toast.push({
                        type: 'error',
                        message: err.data.message
                    })
                    return {
                        success: false,
                        errors: {
                            email: '',
                            password: '',
                            login: err.data.message ?? '',
                        },
                    }
                }

                toast.push({
                    type: 'error',
                    message: 'Something went wrong'
                })

                return {
                    success: false,
                    errors: null,
                }
            }
        }

        async function fetchUser() {
            const res = await api<{ user: User }>('/me')
            user.value = res.user
        }

        async function logout() {
            try {
                await api('/logout', {
                    method: 'POST',
                })
                
                token.value = null
                user.value = null

                toast.push({
                    type: 'success',
                    message: 'You are now logged out',
                })

                return {
                    success: true
                }
            } catch (err: any) {
                toast.push({
                    type: 'error',
                    message: 'Something went wrong',
                })
                return {
                    success: false,
                    errors: null,
                }
            }
        }

        return {
            user,
            token,
            decoded,
            role,
            isTokenExpired,
            isLoggedIn,
            register,
            login,
            fetchUser,
            logout,
        }
    },
    {
        persist: {
            pick: ['token', 'user'],
        },
    }
)
