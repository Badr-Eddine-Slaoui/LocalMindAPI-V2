import type { User } from './user';
import type { Answer } from './answer';

export interface Question {
    id: number
    title: string
    description: string
    answers_count: number
    favorites_count: number
    is_favorited: boolean
    user: User
    answers: Answer[]
    created_at: string
    updated_at: string
}

export interface QuestionData {
    questions: Question[]
}