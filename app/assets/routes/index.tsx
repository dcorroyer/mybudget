import { createFileRoute, useNavigate } from '@tanstack/react-router'

import NotFound from '@/components/not-found'
import { useUser } from '@/features/auth/hooks/useUser'

export const Route = createFileRoute('/')({
  beforeLoad: () => {
    const { user } = useUser()
    const navigate = useNavigate()
    if (!user) navigate({ to: '/auth/login' })
  },
  component: NotFound,
})
