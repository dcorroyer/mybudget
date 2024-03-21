import axios from 'axios'
import React, { createContext, useContext, useEffect, useMemo, useState } from 'react'
import { toast } from '@/components/hooks/useToast'
import { getMe } from '@/api'

interface AuthContextType {
    token: string | null
    getToken: () => string | null
    setToken: (newToken: React.SetStateAction<string | null>) => void
    clearToken: () => void
    getTokenValue: (token: string) => string
    getExpireDateToken: (token: string) => number
    checkTokenValidity: () => void
}

const AuthContext = createContext<AuthContextType>({
    token: null,
    getToken: () => '',
    setToken: () => {},
    clearToken: () => {},
    getTokenValue: () => '',
    getExpireDateToken: () => 0,
    checkTokenValidity: () => {},
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

    // Todo: getTokenValue vs getToken ?
    const getTokenValue = (token: string): string => {
        return JSON.parse(token).token
    }

    const getExpireDateToken = (token: string): number => {
        const tokenParts = token.split('.')
        const payload = tokenParts[1]
        const decodedPayload = atob(payload)
        const decodedPayloadObject = JSON.parse(decodedPayload)

        return decodedPayloadObject.exp * 1000
    }

    const checkTokenValidity = async (): Promise<Response | void> => {
        if (token) {
            try {
                const response = await getMe(getTokenValue(token))

                if (!response.ok) {
                    clearToken()
                    toast({
                        title: 'Session expired',
                        description: 'Your session has expired. Please log in again.',
                        variant: 'destructive',
                    })
                }
            } catch (error) {
                console.error('Error checking token validity:', error)
            }
        }
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
            getTokenValue,
            getExpireDateToken,
            checkTokenValidity,
        }),
        [token],
    )

    return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
}

export const useAuth = () => {
    return useContext(AuthContext)
}

export default AuthProvider
