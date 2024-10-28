import { useCallback } from 'react'
import { useNavigate } from 'react-router-dom'

import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'

import { notifications } from '@mantine/notifications'

import {
  deleteBudgetId,
  getBudgetDetail,
  getBudgetList,
  postBudget,
  postDuplicateBudget,
  postDuplicateBudgetId,
  updateBudgetId,
} from '@/features/budgets/api/budgets'

import { BudgetParams } from '@/features/budgets/types/budgets'

export const useBudget = () => {
  const queryClient = useQueryClient()
  const navigate = useNavigate()

  const useBudgetList = (year: number) => {
    const { data: budgetList, isFetching } = useQuery({
      queryKey: ['budgets', 'list', year],
      queryFn: () => getBudgetList(year),
      enabled: !!year,
    })

    return { budgetList, isFetching }
  }

  const useBudgetDetail = (id: number) => {
    const { data: budget, isFetching } = useQuery({
      queryKey: ['budgets', 'detail', id],
      queryFn: () => getBudgetDetail(id.toString()),
    })

    return { budget, isFetching }
  }

  const createBudget = useCallback((data: BudgetParams) => {
    createBudgetMutation.mutate(data)
  }, [])

  const createBudgetMutation = useMutation({
    mutationFn: postBudget,
    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ['budgets', 'list'],
        refetchType: 'all',
      })
      navigate('/budgets')
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
      queryClient.invalidateQueries({
        queryKey: ['budgets'],
      })
      navigate('/budgets')
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'blue',
        title: 'Successful Update',
        message: 'You have successfully updated the budget',
      })
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
      queryClient.invalidateQueries({
        queryKey: ['budgets'],
      })
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

  const duplicateBudget = useCallback((id: string) => {
    duplicateBudgetMutation.mutate(id)
  }, [])

  const duplicateBudgetMutation = useMutation({
    mutationFn: postDuplicateBudgetId,
    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ['budgets', 'list'],
      })
      navigate('/budgets')
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'blue',
        title: 'Successful Duplication',
        message: 'You have successfully duplicated a budget',
      })
    },
    onError: (error: Error) => {
      console.log('error:', error)
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'red',
        title: 'Error',
        message: 'There was an error during the budget duplication process',
      })
    },
  })

  const duplicateLatestBudget = useCallback(() => {
    duplicateLatestBudgetMutation.mutate()
  }, [])

  const duplicateLatestBudgetMutation = useMutation({
    mutationFn: postDuplicateBudget,
    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ['budgets', 'list'],
      })
      navigate('/budgets')
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'blue',
        title: 'Successful Duplication',
        message: 'You have successfully duplicated a budget',
      })
    },
    onError: (error: Error) => {
      console.log('error:', error)
      notifications.show({
        withBorder: true,
        radius: 'md',
        color: 'red',
        title: 'Error',
        message: 'There was an error during the budget duplication process',
      })
    },
  })

  return {
    useBudgetList,
    useBudgetDetail,
    createBudget,
    updateBudget,
    deleteBudget,
    duplicateBudget,
    duplicateLatestBudget,
    isLoading:
      createBudgetMutation.isPending ||
      updateBudgetMutation.isPending ||
      deleteBudgetMutation.isPending ||
      duplicateBudgetMutation.isPending ||
      duplicateLatestBudgetMutation.isPending,
  }
}
