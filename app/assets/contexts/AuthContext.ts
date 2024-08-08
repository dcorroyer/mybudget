import { readLocalStorageValue } from '@mantine/hooks'

export const useAuthContext = () => {
  const isLogged = () => readLocalStorageValue({ key: 'isAuthenticated' })

  return { isLogged }
}

export type AuthContext = ReturnType<typeof useAuthContext>
