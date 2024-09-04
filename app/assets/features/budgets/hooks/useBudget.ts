import { useCallback } from 'react'

import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useNavigate } from '@tanstack/react-router'

import { notifications } from '@mantine/notifications'

import { deleteBudgetId, getBudgetDetail, getBudgetList, postBudget } from '@/features/budgets/api'
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
  const queryClient = useQueryClient()

  const createBudget = useCallback((data: BudgetParams) => {
    createBudgetMutation.mutate(data)
  }, [])

  const createBudgetMutation = useMutation({
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

  const deleteBudget = useCallback((id: string) => {
    deleteBudgetMutation.mutate(id)
  }, [])

  const deleteBudgetMutation = useMutation({
    mutationFn: deleteBudgetId,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['budgets'] })
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'blue',
        title: 'Successful Deletion',
        message: 'You have successfully deleted a budget',
      })
    },
    onError: (error: Error) => {
      console.log('error:', error)
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'red',
        title: 'Error',
        message: 'There was an error during the budget delete process',
      })
    },
  })

  return {
    createBudget,
    deleteBudget,
  }
}
