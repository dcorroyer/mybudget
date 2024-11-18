import { ApiResponseList } from '@/utils/ApiResponse'
import { client } from '@/utils/client'
import { Transaction } from '../types/transactions'

export async function getTransactionList(accountId: string): Promise<ApiResponseList<Transaction[]>> {
  const response = await client(`/api/accounts/transactions?accountIds[]=${accountId}`, {
    method: 'GET',
  })

  if (!response.ok) return Promise.reject('Failed to get transactions')

  return await response.json()
} 