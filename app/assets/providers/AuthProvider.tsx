import React, { createContext, useContext, useMemo } from 'react'

import { useAuth } from '@/hooks/useAuth'
import { useLocalStorage } from '@mantine/hooks'

interface AuthContextType {
  token: string | null
  setToken: (val: ((prevState: null) => null) | null) => void
  clearToken: () => void
  checkTokenValidity: () => void
}

const AuthContext = createContext<AuthContextType>({
  token: null,
  setToken: () => {},
  clearToken: () => {},
  checkTokenValidity: () => {},
})

const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { authenticate } = useAuth()

  const [token, setToken] = useLocalStorage({ key: 'token', defaultValue: null })

  const checkTokenValidity = () => {
    authenticate()
  }

  const contextValue = useMemo(
    () => ({
      token,
      setToken,
      checkTokenValidity,
      clearToken: () => setToken(null),
    }),
    [token],
  )

  return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
}

export const useAuthProvider = () => {
  return useContext(AuthContext)
}

export default AuthProvider
