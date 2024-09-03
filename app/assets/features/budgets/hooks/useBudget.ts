import { getBudgetDetail, getBudgetList, postBudget } from '@/features/budgets/api'
import { useMutation, useQuery } from '@tanstack/react-query'
import { useCallback } from 'react'

export function useBudgetList() {
  return useQuery({
    queryKey: ['budgets'],
    queryFn: getBudgetList,
  })
}

export function useBudgetDetail(id: number) {
  return useQuery({
    queryKey: ['budgets', { id: id }],
    queryFn: () => getBudgetDetail(id.toString()),
  })
}

export const useBudget = () => {
  const create = useCallback((data) => {
    createBudget.mutate(data)
  }, [])

  const createBudget = useMutation({
    mutationFn: postBudget,
    onSuccess: () => {
      console.log('Budget created')
    },
  })

  return {
    create,
  }
}
