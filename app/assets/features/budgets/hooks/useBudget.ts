import { useCallback } from 'react'

import { useMutation, useQuery } from '@tanstack/react-query'
import { useNavigate } from '@tanstack/react-router'

import { notifications } from '@mantine/notifications'

import { getBudgetDetail, getBudgetList, postBudget } from '@/features/budgets/api'
import { BudgetParams } from '@/features/budgets/types'

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
  const navigate = useNavigate()

  const create = useCallback((data: BudgetParams) => {
    createBudget.mutate(data)
  }, [])

  const createBudget = useMutation({
    mutationFn: postBudget,
    onSuccess: () => {
      navigate({ to: '/budgets' })
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'blue',
        title: 'Successful Creation',
        message: 'You have successfully created a budget',
      })
    },
    onError: (error: Error) => {
      console.log('error:', error)
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'red',
        title: 'Error',
        message: 'There was an error during the budget create process',
      })
    },
  })

  return {
    create,
  }
}
