import { ApiResponse } from '@/utils/ApiResponse'
import { client } from '@/utils/client'
import { SavingsFilterParams, SavingsResponse } from '../types/savings'

export async function getBalanceHistory(
  filters?: SavingsFilterParams,
): Promise<ApiResponse<SavingsResponse>> {
  const searchParams = new URLSearchParams()

  if (filters?.accountIds) {
    filters.accountIds.forEach((id) => searchParams.append('accountIds[]', id.toString()))
  }

  if (filters?.period) {
    searchParams.append('period', filters.period)
  }

  const response = await client(`/api/accounts/balance-history?${searchParams.toString()}`, {
    method: 'GET',
  })

  if (!response.ok) return Promise.reject('Failed to get balance history')

  return await response.json()
}
