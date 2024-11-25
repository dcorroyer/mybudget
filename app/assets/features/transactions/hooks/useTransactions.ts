import { notifications } from '@mantine/notifications'
import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import { useNavigate } from 'react-router-dom'
import {
  deleteTransactionId,
  getTransactionId,
  getTransactionList,
  postTransaction,
  updateTransactionId,
} from '../api/transactions'
import { TransactionFilterParams, TransactionParams } from '../types/transactions'

export function useTransactions() {
  const navigate = useNavigate()
  const queryClient = useQueryClient()

  const useTransactionList = (filters?: TransactionFilterParams) => {
    return useQuery({
      queryKey: ['transactions', filters],
      queryFn: () => getTransactionList(filters),
    })
  }

  const useTransaction = (accountId: number, transactionId: number) => {
    return useQuery({
      queryKey: ['transactions', accountId, transactionId],
      queryFn: () => getTransactionId(accountId, transactionId),
    })
  }

  const { mutate: createTransaction, isPending: isCreateLoading } = useMutation({
    mutationFn: (params: { accountId: number; values: TransactionParams }) =>
      postTransaction(params.accountId, params.values),
    onSuccess: () => {
      notifications.show({
        title: 'Success',
        message: 'Transaction created successfully',
        color: 'green',
      })
      queryClient.invalidateQueries({
        queryKey: ['transactions'],
        refetchType: 'all',
      })
      navigate('/transactions')
    },
    onError: () => {
      notifications.show({
        title: 'Error',
        message: 'Failed to create transaction',
        color: 'red',
      })
    },
  })

  const { mutate: updateTransaction, isPending: isUpdateLoading } = useMutation({
    mutationFn: (params: { accountId: number; transactionId: number; values: TransactionParams }) =>
      updateTransactionId(params.accountId, params.transactionId, params.values),
    onSuccess: () => {
      notifications.show({
        title: 'Success',
        message: 'Transaction updated successfully',
        color: 'green',
      })
      queryClient.invalidateQueries({ queryKey: ['transactions'] })
      navigate('/transactions')
    },
    onError: () => {
      notifications.show({
        title: 'Error',
        message: 'Failed to update transaction',
        color: 'red',
      })
    },
  })

  const { mutate: deleteTransaction, isPending: isDeleteLoading } = useMutation({
    mutationFn: (params: { accountId: number; transactionId: number }) =>
      deleteTransactionId(params.accountId, params.transactionId),
    onSuccess: () => {
      notifications.show({
        title: 'Success',
        message: 'Transaction deleted successfully',
        color: 'green',
      })
      queryClient.invalidateQueries({ queryKey: ['transactions'] })
    },
    onError: () => {
      notifications.show({
        title: 'Error',
        message: 'Failed to delete transaction',
        color: 'red',
      })
    },
  })

  return {
    useTransactionList,
    useTransaction,
    createTransaction,
    updateTransaction,
    deleteTransaction,
    isLoading: isCreateLoading || isUpdateLoading || isDeleteLoading,
  }
}
