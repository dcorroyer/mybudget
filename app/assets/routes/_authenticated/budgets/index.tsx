import { createFileRoute } from '@tanstack/react-router'

import { BudgetList } from '@/components/pages/budgets/list'

export const Route = createFileRoute('/_authenticated/budgets/')({
  component: BudgetList,
})
