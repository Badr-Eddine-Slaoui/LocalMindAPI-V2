import type { User } from "./user";

export interface AuthResponse {
    access_token: string;
    user: User;
}

export interface ReturnData<T>{
    success: boolean;
    data?: T;
    errors?: any;
}