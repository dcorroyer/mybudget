import { useQuery } from '@tanstack/react-query'
import { useEffect } from 'react'
import { getMe } from '../api'

export function useUser() {
  console.log(getMe())
  const { data: user, isFetching } = useQuery({
    queryKey: ['me'],
    queryFn: async () => await getMe(),
  })

  useEffect(() => {
    console.log('userEffect', user)
  }, [user])

  console.log('user', user)

  return {
    user: user ?? null,
  }
}
