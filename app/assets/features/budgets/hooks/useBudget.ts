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
    return useQuery({
      queryKey: ['budgets', year],
      queryFn: () => getBudgetList(year),
      enabled: !!year,
    })
  }

  const useBudgetDetail = (id: number) => {
    return useQuery({
      queryKey: ['budgets', id],
      queryFn: () => getBudgetDetail(id.toString()),
    })
  }

  const { mutate: createBudget, isPending: isCreateLoading } = useMutation({
    mutationFn: postBudget,
    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ['budgets'],
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

  const { mutate: updateBudget, isPending: isUpdateLoading } = useMutation({
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

  const { mutate: deleteBudget, isPending: isDeleteLoading } = useMutation({
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

  const { mutate: duplicateBudget, isPending: isDuplicateLoading } = useMutation({
    mutationFn: postDuplicateBudgetId,
    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ['budgets'],
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

  const { mutate: duplicateLatestBudget, isPending: isDuplicateLatestLoading } = useMutation({
    mutationFn: postDuplicateBudget,
    onSuccess: () => {
      queryClient.invalidateQueries({
        queryKey: ['budgets'],
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
      isCreateLoading ||
      isUpdateLoading ||
      isDeleteLoading ||
      isDuplicateLoading ||
      isDuplicateLatestLoading,
  }
}
