import React from 'react'

import { getBudgetDetail } from '@/api'
import { createFileRoute } from '@tanstack/react-router'

export const Route = createFileRoute('/_authenticated/budgets/$id/')({
  component: BudgetDetail,
  loader: async ({ params }) => await getBudgetDetail(params.id),
})

function BudgetDetail() {
  const budget = Route.useLoaderData()

  return (
    <div>
      {budget.data.name} Saving Capacity: {budget.data.savingCapacity}
    </div>
  )
}
