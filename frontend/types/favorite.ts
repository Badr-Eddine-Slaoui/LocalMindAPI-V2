import type { Question } from './question';
import type { Answer } from './answer';

export interface Favorite {
    id: number
    question_id: number
    user_id: number
    question: Question
    created_at: string
    updated_at: string
}

export interface FavoriteData {
    favorites: Favorite[]
}