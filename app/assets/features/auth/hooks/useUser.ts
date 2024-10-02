import { useQuery } from '@tanstack/react-query'
import { getMe } from '../api/auth'

export function useUser() {
  const { data: user, isFetching } = useQuery({
    queryKey: ['me'],
    queryFn: getMe,
  })

  return { user, isFetching }
}
