import { defineStore } from 'pinia';
import type { Toast, ToastType } from '~~/types/toast';


export const useToastStore = defineStore(
    'toast',
    () => {
        const toasts = ref<Toast[]>([])

        function push(data: { type: ToastType; message: string}){
            const id: number = Date.now()
            
            toasts.value.push({
                id,
                type: data.type,
                message: data.message,
            })

            setTimeout(() => {
                remove(id)
            }, 5000)
        }

        function remove(id: number){
            toasts.value = toasts.value.filter(toast => toast.id !== id) as Toast[]
        }

        return {
            toasts,
            push,
            remove
        }
    }
)