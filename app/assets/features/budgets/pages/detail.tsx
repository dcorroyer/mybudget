import React from 'react'

import { ApiResponse } from '@/utils/ApiResponse'

import { BudgetDetails } from '@/features/budgets/types'

export function BudgetDetail({ budget }: { budget: ApiResponse<BudgetDetails> }) {
  return (
    <div>
      {budget.data.name} Saving Capacity: {budget.data.savingCapacity}
    </div>
  )
}
