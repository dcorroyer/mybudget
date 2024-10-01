import { useQuery } from '@tanstack/react-query'
import { getMe } from '../api'

export function useUser() {
  const { data: user, isFetching } = useQuery({
    queryKey: ['me'],
    queryFn: getMe,
  })

  return { user, isFetching }
}
