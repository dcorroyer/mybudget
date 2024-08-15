import { createFileRoute, redirect } from '@tanstack/react-router'

import { Register } from '@/features/auth/pages/register'

export const Route = createFileRoute('/register')({
  beforeLoad: async ({ context }) => {
    const { isLogged } = context.authentication
    if (isLogged()) {
      throw redirect({ to: '/' })
    }
  },
  component: Register,
})
