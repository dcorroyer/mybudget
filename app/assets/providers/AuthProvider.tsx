import React, { createContext, useContext, useState } from 'react'

import { getMe } from '@/api/auth'

import { useLocalStorage } from '@mantine/hooks'

interface AuthContextType {
  token: string | null
  setToken: (val: ((prevState: null) => null) | null) => void
  clearToken: () => void
  auth: () => void
  isAuthenticated: boolean
  loading: boolean
}

const AuthContext = createContext<AuthContextType>({
  token: null,
  setToken: () => {},
  clearToken: () => {},
  auth: () => {},
  isAuthenticated: false,
  loading: true,
})

const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [token, setToken] = useLocalStorage({ key: 'token', defaultValue: null })
  const [isAuthenticated, setIsAuthenticated] = useState(false)
  const [loading, setLoading] = useState(true)

  const clearToken = () => {
    setToken(null)
    setIsAuthenticated(false)
  }

  const auth = () => {
    getMe()
      .then(() => {
        setIsAuthenticated(true)
      })
      .catch(() => {
        clearToken()
      })
      .finally(() => {
        setLoading(false)
      })
  }

  const contextValue = {
    token,
    setToken,
    clearToken,
    isAuthenticated,
    auth,
    loading,
  }

  return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
}

export const useAuthProvider = () => {
  return useContext(AuthContext)
}

export default AuthProvider
