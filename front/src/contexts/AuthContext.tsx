import { createContext, ReactNode, useContext, useEffect, useState } from 'react'
import { RegisterInput } from '../components/auth/schemas'

interface User {
  id: string
  username: string
  roles: string[]
  // Add other user properties as needed
}

interface AuthContextType {
  user: User | null
  token: string | null
  login: (token: string) => Promise<void>
  register: (values: RegisterInput) => Promise<void>
  logout: () => void
  isAuthenticated: boolean
}

const AuthContext = createContext<AuthContextType | undefined>(undefined)

export function AuthProvider({ children }: { children: ReactNode }) {
  const [token, setToken] = useState<string | null>(localStorage.getItem('token'))
  const [user, setUser] = useState<User | null>(null)

  useEffect(() => {
    if (token) {
      const b64 = token.split('.')[1]
      const payload = JSON.parse(atob(b64))
      setUser(payload)
    }
  }, [token])

  const login = async (token: string) => {
    localStorage.setItem('token', token)
    setToken(token)
  }

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const register = async (values: RegisterInput) => {
    // TODO: Implement actual API call
    const mockToken = 'mock-jwt-token'

    localStorage.setItem('token', mockToken)
    setToken(mockToken)
  }

  const logout = () => {
    localStorage.removeItem('token')
    setToken(null)
    setUser(null)
  }

  return (
    <AuthContext.Provider
      value={{
        user,
        token,
        login,
        register,
        logout,
        isAuthenticated: !!token,
      }}
    >
      {children}
    </AuthContext.Provider>
  )
}

export function useAuth() {
  const context = useContext(AuthContext)
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider')
  }
  return context
}
