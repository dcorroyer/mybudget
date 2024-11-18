import { useQuery } from '@tanstack/react-query'
import { getBalanceHistory } from '../api/savings'
import { SavingsFilterParams } from '../types/savings'

export function useSavings() {
  const useBalanceHistory = (filters?: SavingsFilterParams) => {
    return useQuery({
      queryKey: ['savings', 'balance-history', filters],
      queryFn: () => getBalanceHistory(filters),
    })
  }

  return {
    useBalanceHistory,
  }
} 