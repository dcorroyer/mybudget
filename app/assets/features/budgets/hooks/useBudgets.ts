import { ExpenseResponse } from '@/api/models'
import { useMemo } from 'react'
import { GroupedExpenses } from '../types/budgetTypes'

export const useGroupedExpenses = (expenses: ExpenseResponse[]): GroupedExpenses[] => {
  return useMemo(() => {
    const grouped = expenses.reduce(
      (acc, expense) => {
        if (!acc[expense.category]) {
          acc[expense.category] = []
        }
        acc[expense.category].push(expense)
        return acc
      },
      {} as Record<string, ExpenseResponse[]>,
    )

    return Object.entries(grouped).map(([category, items]) => ({
      category,
      items,
      total: items.reduce((sum, item) => sum + item.amount, 0),
    }))
  }, [expenses])
}
