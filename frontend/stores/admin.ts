import { defineStore } from 'pinia';
import { ref } from 'vue';
import type { ReturnData } from '~~/types/api';

export const useAdminStore = defineStore(
    'admin',
    () => {
        const api = useApi()
        const users_count = ref(0)

        async function fetchAdminData() {
            const res = await api<ReturnData<{ users_count: number }>>('/admin')
            users_count.value = res.data?.users_count as number
        }

        return {
            users_count,
            fetchAdminData
        }
    },
    {
        persist: {
            pick: ['users_count'],
        },
    }
)
