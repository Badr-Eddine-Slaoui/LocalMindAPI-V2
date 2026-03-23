import { defineStore } from 'pinia';
import { ref } from 'vue';
import type { ReturnData } from '../types/api';
import type { Favorite, FavoriteData } from '../types/favorite';
import { useQuestion } from './question';
import type { Question } from '~~/types/question';

export const useFavorite = defineStore(
    'favorite',
    () => {
        const api = useApi()

        const favorites = ref<Favorite[] | null>(null)

        async function fetchFavorites(): Promise<void> {
            const res = await api<ReturnData<FavoriteData>>('/favorites')
            favorites.value = res.data?.favorites as Favorite[]
        }

        async function favorite(question_id: number): Promise<ReturnData<any>> {
            try{
                const res = await api<ReturnData<{ favorite: Favorite, message: string}>>(`/favorites/${question_id}`, {
                    method: 'POST'
                })

                const question_store = useQuestion();
                const question = question_store.questions?.find((question) => question.id === question_id) as Question

                question.favorites_count += 1
                question.is_favorited = true

                if(question?.id == question_store.question?.id){
                    question_store.question.favorites_count += 1
                    question_store.question.is_favorited = true
                }

                favorites.value?.unshift(res?.data?.favorite as Favorite)

                return {
                    success: true,
                    data: res.data?.message
                }

            } catch (err: any) {
                return {
                    success: false,
                    errors: {
                        title: '',
                        description: '',
                        message: err.data.message
                    },
                }
            }
        }

        async function unfavorite(question_id: number): Promise<ReturnData<any>> {
            try{
                const res = await api<ReturnData<{ message: string }>>(`/favorites/${question_id}`, {
                    method: 'DELETE'
                })

                const question_store = useQuestion();
                const question = question_store.questions?.find((question) => question.id === question_id) as Question

                question.favorites_count -= 1
                question.is_favorited = false

                if(question?.id == question_store.question?.id){
                    question_store.question.favorites_count -= 1
                    question_store.question.is_favorited = false
                }

                const index: number = favorites.value?.findIndex(y => y.question_id === question_id) as number
                favorites.value?.splice(index, 1)

                return {
                    success: true,
                    data: res.data?.message
                }

            } catch (err: any) {
                return {
                    success: false,
                    errors: {
                        title: '',
                        description: '',
                        message: err.data.message
                    },
                }
            }
        }

        return {
            favorites,
            fetchFavorites,
            favorite,
            unfavorite
        }
    },
    {
        persist: {
            pick: ['favorites'],
        },
    }
)
