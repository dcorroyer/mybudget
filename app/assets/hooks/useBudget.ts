import { getBudgetList } from '@/api'
import { useQuery } from '@tanstack/react-query'

export function useBudget() {
  const budgetList = useQuery({
    queryKey: ['budgets'],
    queryFn: getBudgetList,
  })

  return {
    budgetList,
  }
}
