import { notifications } from '@mantine/notifications'
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useNavigate } from 'react-router-dom'

import {
  deleteAccountId,
  getAccountDetail,
  getAccountList,
  postAccount,
  updateAccountId,
} from '../api/accounts'

import { AccountParams } from '../types/accounts'

export function useAccount() {
  const navigate = useNavigate()
  const queryClient = useQueryClient()

  const { mutate: createAccount, isPending: isCreateLoading } = useMutation({
    mutationFn: (values: AccountParams) => postAccount(values),
    onSuccess: () => {
      notifications.show({
        title: 'Success',
        message: 'Account created successfully',
        color: 'green',
      })
      queryClient.invalidateQueries({ queryKey: ['accounts'], refetchType: 'all' })
      navigate('/')
    },
    onError: () => {
      notifications.show({
        title: 'Error',
        message: 'Failed to create account',
        color: 'red',
      })
    },
  })

  const { mutate: updateAccount, isPending: isUpdateLoading } = useMutation({
    mutationFn: (params: { id: number; values: AccountParams }) =>
      updateAccountId(params.id.toString(), params.values),
    onSuccess: () => {
      notifications.show({
        title: 'Success',
        message: 'Account updated successfully',
        color: 'green',
      })
      queryClient.invalidateQueries({ queryKey: ['accounts'] })
      navigate('/')
    },
    onError: () => {
      notifications.show({
        title: 'Error',
        message: 'Failed to update account',
        color: 'red',
      })
    },
  })

  const { mutate: deleteAccount, isPending: isDeleteLoading } = useMutation({
    mutationFn: (id: string) => deleteAccountId(id),
    onSuccess: () => {
      notifications.show({
        title: 'Success',
        message: 'Account deleted successfully',
        color: 'green',
      })
      queryClient.invalidateQueries({ queryKey: ['accounts'] })
    },
    onError: () => {
      notifications.show({
        title: 'Error',
        message: 'Failed to delete account',
        color: 'red',
      })
    },
  })

  const useAccountList = () => {
    return useQuery({
      queryKey: ['accounts'],
      queryFn: () => getAccountList(),
    })
  }

  const useAccountDetail = (id: string) => {
    return useQuery({
      queryKey: ['accounts', id],
      queryFn: () => getAccountDetail(id),
    })
  }

  return {
    createAccount,
    updateAccount,
    deleteAccount,
    useAccountList,
    useAccountDetail,
    isLoading: isCreateLoading || isUpdateLoading || isDeleteLoading,
  }
}
