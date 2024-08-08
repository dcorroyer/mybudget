import { createFileRoute } from '@tanstack/react-router'

import { Register } from '@/components/auth/register'

export const Route = createFileRoute('/register/')({
  component: Register,
})
