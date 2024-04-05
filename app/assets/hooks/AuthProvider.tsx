import React, { createContext, useContext, useMemo, useState } from 'react'
import { toast } from '@/components/hooks/useToast'
import { getMe } from '@/api'

interface AuthContextType {
    token: string | null
    getToken: () => string | null
    setToken: (newToken: React.SetStateAction<string | null>) => void
    clearToken: () => void
    checkTokenValidity: () => void
}

const AuthContext = createContext<AuthContextType>({
    token: null,
    getToken: () => '',
    setToken: () => {},
    clearToken: () => {},
    checkTokenValidity: () => {},
})

const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [token, setToken_] = useState<string | null>(() => localStorage.getItem('token'))

    const getToken = (): string | null => {
        const tokenValue = JSON.parse(token as string)
        return tokenValue?.token || null
    }

    const setToken = (newToken: React.SetStateAction<string | null>): void => {
        setToken_(newToken)
        localStorage.setItem('token', newToken)
    }

    const clearToken = (): void => {
        setToken_(null)
        localStorage.removeItem('token')
    }

    const checkTokenValidity = async (): Promise<void> => {
        if (token) {
            try {
                const response = await getMe()

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

    const contextValue = useMemo(
        () => ({
            token,
            getToken,
            setToken,
            clearToken,
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
