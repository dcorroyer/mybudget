import { useCallback } from 'react'

import { useMutation } from '@tanstack/react-query'

import { useLocalStorage } from '@mantine/hooks'
import { notifications } from '@mantine/notifications'

import { postLogin, postRegister } from '@/features/auth/api/auth'
import { useRouter } from '@tanstack/react-router'

export const useAuth = () => {
  const [token, setToken] = useLocalStorage({ key: 'token', defaultValue: null })
  const [isAuthenticated, setIsAuthenticated] = useLocalStorage({
    key: 'isAuthenticated',
    defaultValue: false,
  })
  const router = useRouter()

  const login = useCallback((email: string, password: string) => {
    authLogin.mutate({ email, password })
  }, [])

  const authLogin = useMutation({
    mutationFn: postLogin,
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
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
      setIsAuthenticated(true)

      router.invalidate()

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
    setToken(null)
    setIsAuthenticated(false)

    router.invalidate()

    notifications.show({
      withBorder: true,
      radius: 'md',
      color: 'blue',
      title: 'Logout',
      message: 'You are now logged out',
    })
  }

  return {
    login,
    logout,
    register,
    isAuthenticated,
    token,
  }
}
