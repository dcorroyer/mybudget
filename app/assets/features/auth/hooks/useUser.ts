import { useQuery } from '@tanstack/react-query'
import { useEffect } from 'react'
import { getMe } from '../api'
import { User } from '../types'
import * as useUserLocalStorage from './useUserLocalStorage'

interface IUseUser {
  user: User | null
}

export function useUser(): IUseUser {
  const { data: user } = useQuery({
    queryKey: ['me'],
    queryFn: () => getMe(),
    enabled: !!useUserLocalStorage.getUser(),
  })

  useEffect(() => {
    if (!user) useUserLocalStorage.removeUser()
    else useUserLocalStorage.saveUser(user)
  }, [user])

  return {
    user: user ?? null,
  }
}
