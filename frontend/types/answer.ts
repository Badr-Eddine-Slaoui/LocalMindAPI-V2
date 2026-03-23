import type { User } from './user';
export interface Answer {
    id: number
    answer: string
    question_id: number
    user_id: number
    user: User
    created_at: string
    updated_at: string
}

export interface AnswerData {
    answers: Answer[]
}