import { createFileRoute, redirect } from '@tanstack/react-router'

import { Login } from '@/components/auth/login'

export const Route = createFileRoute('/login')({
  beforeLoad: async ({ context }) => {
    const { isLogged } = context.authentication
    if (isLogged()) {
      throw redirect({ to: '/' })
    }
  },
  component: Login,
})
