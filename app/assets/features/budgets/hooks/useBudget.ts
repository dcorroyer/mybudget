import { useCallback } from 'react'

import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'

import { notifications } from '@mantine/notifications'

import {
  deleteBudgetId,
  getBudgetDetail,
  getBudgetList,
  postBudget,
  updateBudgetId,
} from '@/features/budgets/api/budgets'
import { BudgetParams } from '@/features/budgets/types/budgets'
import { Navigate } from 'react-router-dom'

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
  const queryClient = useQueryClient()

  const createBudget = useCallback((data: BudgetParams) => {
    createBudgetMutation.mutate(data)
  }, [])

  const createBudgetMutation = useMutation({
    mutationFn: postBudget,
    onSuccess: () => {
      Navigate({ to: '/budgets' })
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

  const updateBudget = useCallback((id: number, data: BudgetParams) => {
    updateBudgetMutation.mutate({ id, ...data })
  }, [])

  const updateBudgetMutation = useMutation({
    mutationFn: ({ id, ...data }: { id: number } & BudgetParams) =>
      updateBudgetId(id.toString(), data),
    onSuccess: () => {
      Navigate({ to: '/budgets' })
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'blue',
        title: 'Successful Update',
        message: 'You have successfully updated the budget',
      })
      queryClient.invalidateQueries({ queryKey: ['budgets'] })
    },
    onError: (error: Error) => {
      console.log('error:', error)
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'red',
        title: 'Error',
        message: 'There was an error during the budget update process',
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
    updateBudget,
    deleteBudget,
  }
}
