import React, { createContext, useCallback, useContext } from 'react'

import { getMe } from '@/api/auth'

import { useLocalStorage } from '@mantine/hooks'
import { notifications } from '@mantine/notifications'

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
  const [token, setToken] = useLocalStorage({ key: 'token', defaultValue: null })

  const clearToken = () => {
    setToken(null)
  }

  const checkTokenValidity = useCallback(async () => {
    try {
      await getMe()
    } catch (error) {
      console.log('error:', error)

      clearToken()

      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'red',
        title: 'Error',
        message: 'There was an error while fetching user data',
      })
    }
  }, [])

  const contextValue = {
    token,
    setToken,
    checkTokenValidity,
    clearToken,
  }

  return <AuthContext.Provider value={contextValue}>{children}</AuthContext.Provider>
}

export const useAuthProvider = () => {
  return useContext(AuthContext)
}

export default AuthProvider
