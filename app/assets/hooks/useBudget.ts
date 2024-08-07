import { getBudgetDetail, getBudgetList } from '@/api'
import { useQuery } from '@tanstack/react-query'

export function useBudgetList() {
  return useQuery({
    queryKey: ['budgets'],
    queryFn: getBudgetList,
  })
}

export function useBudgetDetail(id: number) {
  return useQuery({
    queryKey: ['budgets', { id: id }],
    queryFn: () => getBudgetDetail(id),
  })
}
