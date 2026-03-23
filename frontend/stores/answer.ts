import { defineStore } from 'pinia';
import type { ReturnData } from '../types/api';
import type { Answer } from '../types/answer';
import { useQuestion } from './question';
import type { Question } from '~~/types/question';

export const useAnswer = defineStore(
    'answer',
    () => {

        const api = useApi()

        async function createAnswer(question_id: number, data: { answer: string; }): Promise<ReturnData<any>> {
            try{
                const res = await api<ReturnData<{ answer: Answer, message: string}>>(`/answers/${question_id}`, {
                    method: 'POST',
                    body: data
                })

                const question_store = useQuestion();
                const question = question_store.questions?.find((question) => question.id == question_id) as Question

                question.answers_count += 1
                question.answers.unshift(res.data?.answer as Answer)

                if(question?.id == question_store.question?.id){
                    question_store.question.answers_count += 1
                    question_store.question.answers.unshift(res.data?.answer as Answer)
                }

                return {
                    success: true,
                    data: res.data?.message
                }

            } catch (err: any) {
                if (err?.data?.errors) {
                    if (err.data.errors.answer) {
                        let { answer } = err.data.errors;
                        answer = answer ? answer[0] : "";

                        return {
                            success: false,
                            errors: {
                                answer
                            },
                        }
                    }
                }
                return {
                    success: false,
                    errors: {
                        answer: '',
                        message: err.data.message
                    },
                }
            }
        }

        async function updateAnswer(question_id: number, id: number, data: { answer: string; }): Promise<ReturnData<any>> {
            try{
                const res = await api<ReturnData<{ answer: Answer, message: string}>>(`/answers/${id}`, {
                    method: 'PUT',
                    body: data
                })

                const question_store = useQuestion();
                const question = question_store.questions?.find((question) => question.id === question_id) as Question

                let answer = question.answers?.find((answer) => answer.id === id) as Answer
                Object.assign(answer, res.data?.answer as Answer)

                if(question?.id == question_store.question?.id){
                    let answer = question_store.question.answers?.find((answer) => answer.id === id) as Answer
                    Object.assign(answer, res.data?.answer as Answer)
                }

                return {
                    success: true,
                    data: res.data?.message
                }

            } catch (err: any) {
                if (err?.data?.errors) {
                    if (err.data.errors.answer) {
                        let { answer } = err.data.errors;
                        answer = answer ? answer[0] : "";

                        return {
                            success: false,
                            errors: {
                                answer
                            },
                        }
                    }
                }
                return {
                    success: false,
                    errors: {
                        answer: '',
                        message: err.data.message
                    },
                }
            }
        }

        async function deleteAnswer(question_id: number, id: number): Promise<ReturnData<any>> {
            try {
                await api<ReturnData<{ answer: Answer }>>(`/answers/${id}`, {
                    method: 'DELETE'
                })

                const question_store = useQuestion();
                const question = question_store.questions?.find((question) => question.id === question_id) as Question

                const index: number = question.answers?.findIndex(y => y.id === id) as number
                question.answers?.splice(index, 1)

                if(question?.id == question_store.question?.id){
                    const index: number = question_store.question.answers?.findIndex(y => y.id === id) as number
                    question_store.question.answers?.splice(index, 1)
                }

                return {
                    success: true
                }
            } catch (err: any) {
                if (err?.data?.message) {
                    return {
                        success: false,
                        errors: {
                            answer: '',
                            message: err.data.message
                        },
                    }
                }

                return {
                    success: false,
                    errors: null,
                }
            }
        }

        return {
            createAnswer,
            updateAnswer,
            deleteAnswer
        }
    }
)
