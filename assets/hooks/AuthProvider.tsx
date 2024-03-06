import axios from 'axios'
import React, { createContext, useContext, useEffect, useMemo, useState } from 'react'

interface AuthContextType {
    token: string | null
    getToken: () => string | null
    setToken: (newToken: React.SetStateAction<string | null>) => void
    clearToken: () => void
    expireDateToken: (token: string) => number
}

const AuthContext = createContext<AuthContextType>({
    token: null,
    getToken: () => '',
    setToken: () => {},
    clearToken: () => {},
    expireDateToken: () => 0,
})

const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [token, setToken_] = useState<string | null>(localStorage.getItem('token'))

    const getToken = (): string => {
        const tokenValue = JSON.parse(token as string)

        return tokenValue.token
    }
    const setToken = (newToken: React.SetStateAction<string | null>): void => {
        setToken_(newToken)
    }

    const clearToken = (): void => {
        setToken_(null)
    }

    const expireDateToken = (token: string): number => {
        const base64Url = token.split('.')[1]
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/')
        const jsonPayload = decodeURIComponent(
            atob(base64)
                .split('')
                .map((c: string) => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2))
                .join(''),
        )
        const expireDate = JSON.parse(jsonPayload).exp * 1000

        return expireDate
    }

    useEffect(() => {
        if (token) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
            localStorage.setItem('token', token)
        } else {
            delete axios.defaults.headers.common['Authorization']
            localStorage.removeItem('token')
        }
    }, [token])

    const contextValue = useMemo(
        () => ({
            token,
            getToken,
            setToken,
            clearToken,
            expireDateToken,
        }),
        [token],
    )

    return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
}

export const useAuth = () => {
    return useContext(AuthContext)
}

export default AuthProvider
