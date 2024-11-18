import { useQuery } from '@tanstack/react-query'
import { getTransactionList } from '../api/transactions'

export const useTransactions = () => {
  const useTransactionList = (accountId: string) => {
    return useQuery({
      queryKey: ['transactions', accountId],
      queryFn: () => getTransactionList(accountId),
    })
  }

  return {
    useTransactionList,
  }
} 