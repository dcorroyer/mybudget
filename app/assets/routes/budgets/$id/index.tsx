import React from 'react'

import { BudgetDetail } from '@/features/budgets/pages/detail'
import { getBudgetDetail } from '@/features/budgets/api'
import { createFileRoute } from '@tanstack/react-router'

export const Route = createFileRoute('/budgets/$id/')({
  component: () => {
    return <BudgetDetail budget={Route.useLoaderData()} />
  },
  loader: async ({ params }) => await getBudgetDetail(params.id.toString()),
})
