import axios from 'axios'
import React, { createContext, useContext, useEffect, useMemo, useState } from 'react'

interface AuthContextType {
    token: string | null
    getToken: () => string | null
    setToken: (newToken: React.SetStateAction<string | null>) => void
    clearToken: () => void
}

const AuthContext = createContext<AuthContextType>({
    token: null,
    getToken: () => '',
    setToken: () => {},
    clearToken: () => {},
})

const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [token, setToken_] = useState<string | null>(localStorage.getItem('token'))

    const getToken = () => {
        const tokenValue = JSON.parse(token as string)
        return tokenValue.token
    }
    const setToken = (newToken: React.SetStateAction<string | null>) => {
        setToken_(newToken)
    }

    const clearToken = () => {
        setToken_(null)
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
        }),
        [token],
    )

    return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
}

export const useAuth = () => {
    return useContext(AuthContext)
}

export default AuthProvider
