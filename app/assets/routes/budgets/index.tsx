import { createFileRoute } from '@tanstack/react-router'

import { BudgetList } from '@/features/budgets/pages/list'

export const Route = createFileRoute('/budgets/')({
  component: BudgetList,
})
