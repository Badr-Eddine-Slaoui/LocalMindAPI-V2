import { defineStore } from 'pinia';
import { ref } from 'vue';
import type { ReturnData } from '../types/api';
import type { Question, QuestionData } from '../types/question';

export const useQuestion = defineStore(
    'question',
    () => {
        const api = useApi()
        const questions = ref<Question[] | null>(null)
        const question = ref<Question | null>(null)

        async function fetchQuestions(): Promise<void> {
            const res = await api<ReturnData<QuestionData>>('/questions')
            questions.value = res.data?.questions as Question[]
        }

        async function fetchQuestion(id: number): Promise<void> {
            const res = await api<ReturnData<{ question: Question}>>(`/questions/${id}`)
            question.value = res.data?.question as Question
        }

        async function createQuestion(data: { title: string; description: string; }): Promise<ReturnData<any>> {
            try{
                const res = await api<ReturnData<{ question: Question, message: string}>>('/questions', {
                    method: 'POST',
                    body: data
                })

                questions.value?.push(res.data?.question as Question)

                return {
                    success: true,
                    data: res.data?.message
                }

            } catch (err: any) {
                if (err?.data?.errors) {
                    if (err.data.errors.title || err.data.errors.description) {
                        let { title, description } = err.data.errors;
                        title = title ? title[0] : "";
                        description = description ? description[0] : "";

                        return {
                            success: false,
                            errors: {
                                title,
                                description
                            },
                        }
                    }
                }
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

        async function updateQuestion(id: number, data: { title: string; description: string; }): Promise<ReturnData<any>> {
            try{
                const res = await api<ReturnData<{ question: Question, message: string}>>(`/questions/${id}`, {
                    method: 'PUT',
                    body: data
                })

                if(!questions.value?.length){
                    await fetchQuestions()
                }

                const restoredQuestion = res.data?.question as Question
                const question = questions.value?.find(y => y.id === id) as Question

                Object.assign(question, restoredQuestion)
                return {
                    success: true,
                    data: res.data?.message
                }

            } catch (err: any) {
                if (err?.data?.errors) {
                    if (err.data.errors.title || err.data.errors.description) {
                        let { title, description } = err.data.errors;
                        title = title ? title[0] : "";
                        description = description ? description[0] : "";

                        return {
                            success: false,
                            errors: {
                                title,
                                description
                            },
                        }
                    }
                }
                return {
                    success: false,
                    errors: {
                        title: '',
                        description: '',
                        message: err.message
                    },
                }
            }
        }

        async function deleteQuestion(id: number): Promise<void> {
            await api<ReturnData<{ question: Question }>>(`/questions/${id}`, {
                method: 'DELETE'
            })

            if(!questions.value?.length){
                await fetchQuestions()
            }

            const index: number = questions.value?.findIndex(y => y.id === id) as number
            questions.value?.splice(index, 1)
        }

        return {
            questions,
            question,
            fetchQuestions,
            fetchQuestion,
            createQuestion,
            updateQuestion,
            deleteQuestion
        }
    },
    {
        persist: {
            pick: ['questions'],
        },
    }
)
