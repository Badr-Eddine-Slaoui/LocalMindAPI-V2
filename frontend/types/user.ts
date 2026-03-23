import type { Question } from "./question"

export interface User {
    id: number
    name: string
    email: string
    role: string
    questions: Question[]
    favorites: Question[]
    created_at: string
    updated_at: string
}

export interface UserData {
    users: User[]
    archived_users: User[]
}

