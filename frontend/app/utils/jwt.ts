export const decodeJwt = (token: string) => {
    try {
        return JSON.parse(atob(token.split('.')[1] as string))
    } catch {
        return null
    }
}
