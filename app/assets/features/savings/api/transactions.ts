import { ApiResponse, ApiResponseList } from '@/utils/ApiResponse'
import { client } from '@/utils/client'
import { Transaction, TransactionFilterParams, TransactionParams } from '../types/transactions'

export async function getTransactionList(
  filters?: TransactionFilterParams,
): Promise<ApiResponseList<Transaction[]>> {
  const accountIdsParam = filters?.accountIds?.map((id) => `accountIds[]=${id}`).join('&')
  const url = `/api/accounts/transactions${accountIdsParam ? `?${accountIdsParam}` : ''}`

  const response = await client(url, {
    method: 'GET',
  })

  if (!response.ok) return Promise.reject('Failed to get transactions')

  return await response.json()
}

export async function postTransaction(
  accountId: number,
  values: TransactionParams,
): Promise<ApiResponse<Transaction>> {
  const response = await client(`/api/accounts/${accountId}/transactions`, {
    method: 'POST',
    body: JSON.stringify(values),
  })

  if (!response.ok) return Promise.reject('Failed to create transaction')

  return await response.json()
}

export async function updateTransactionId(
  accountId: number,
  transactionId: number,
  values: TransactionParams,
): Promise<ApiResponse<Transaction>> {
  const response = await client(`/api/accounts/${accountId}/transactions/${transactionId}`, {
    method: 'PUT',
    body: JSON.stringify(values),
  })

  if (!response.ok) return Promise.reject('Failed to update transaction')

  return await response.json()
}

export async function deleteTransactionId(accountId: number, transactionId: number): Promise<void> {
  const response = await client(`/api/accounts/${accountId}/transactions/${transactionId}`, {
    method: 'DELETE',
  })

  if (!response.ok) return Promise.reject('Failed to delete transaction')
}

export async function getTransactionId(
  accountId: number,
  transactionId: number,
): Promise<ApiResponse<Transaction>> {
  const response = await client(`/api/accounts/${accountId}/transactions/${transactionId}`, {
    method: 'GET',
  })

  if (!response.ok) return Promise.reject('Failed to get transaction')

  return await response.json()
}
