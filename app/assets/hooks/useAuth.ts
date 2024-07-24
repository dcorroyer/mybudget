import { postLogin, postRegister } from '@/api/auth'
import { useAuthProvider } from '@/providers/AuthProvider'
import { notifications } from '@mantine/notifications'
import { useMutation } from '@tanstack/react-query'
import { useCallback } from 'react'
import { useNavigate } from 'react-router-dom'

export function useAuth() {
  const navigate = useNavigate()
  const { setToken, clearToken } = useAuthProvider()

  const login = useCallback((email: string, password: string) => {
    authLogin.mutate({ email, password })
  }, [])

  const authLogin = useMutation({
    mutationFn: postLogin,
    onSuccess: (data: any) => {
      if (data.error !== undefined) {
        notifications.show({
          withBorder: true,
          radius: 'md',
          color: 'red',
          title: 'Error',
          message: 'Invalid credentials',
        })
        return
      }

      setToken(data.token)
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'blue',
        title: 'Successful Login',
        message: 'You are now logged in',
      })
    },
    onError: (error: Error) => {
      console.log('error:', error)
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'red',
        title: 'Error',
        message: 'There was an error during the login process',
      })
    },
  })

  const register = useCallback(
    (firstName: string, lastName: string, email: string, password: string) => {
      authRegister.mutate({ firstName, lastName, email, password })
    },
    [],
  )

  const authRegister = useMutation({
    mutationFn: postRegister,
    onSuccess: (data, variables) => {
      login(variables.email, variables.password)
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'blue',
        title: 'Successful Register',
        message: 'You are now registered',
      })
      return
    },
    onError: (error: Error) => {
      console.log('error:', error)
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'red',
        title: 'Error',
        message: 'There was an error during the register process',
      })
    },
  })

  const logout = () => {
    clearToken()

    notifications.show({
      withBorder: true,
      radius: 'md',
      color: 'blue',
      title: 'Logout',
      message: 'You are now logged out',
    })

    navigate('/login')
  }

  return {
    login,
    logout,
    register,
  }
}
