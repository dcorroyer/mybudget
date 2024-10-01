import { createFileRoute } from '@tanstack/react-router'

import { Register } from '@/features/auth/pages/register'

export const Route = createFileRoute('/auth/register')({
  component: Register,
})
