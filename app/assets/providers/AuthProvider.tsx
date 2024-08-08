import React, { createContext, useContext } from 'react'

import { useLocalStorage } from '@mantine/hooks'

interface AuthContextType {
  token: string | null
  setToken: (val: ((prevState: null) => null) | null) => void
  clearToken: () => void
}

const AuthContext = createContext<AuthContextType>({
  token: null,
  setToken: () => {},
  clearToken: () => {},
})

const AuthProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [token, setToken] = useLocalStorage({ key: 'token', defaultValue: null })

  const clearToken = () => {
    setToken(null)
  }

  const contextValue = {
    token,
    setToken,
    clearToken,
  }

  return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
}

export const useAuthProvider = () => {
  return useContext(AuthContext)
}

export default AuthProvider
