import { createFileRoute } from '@tanstack/react-router'

import { BudgetCreate } from '@/features/budgets/pages/create'

export const Route = createFileRoute('/budgets/create')({
  component: BudgetCreate,
})
