import { ApiErrorResponse } from '@/utils/ApiErrorResponse'
import { ApiResponse, ApiResponseList } from '@/utils/ApiResponse'
import { client } from '@/utils/client'
import { Account, AccountParams } from '../types/accounts'

export async function getAccountList(): Promise<ApiResponseList<Account[]>> {
  const response = await client('/api/accounts', {
    method: 'GET',
  })

  if (!response.ok) return Promise.reject('Failed to get accounts')

  return await response.json()
}

export async function getAccountDetail(id: string): Promise<ApiResponse<Account>> {
  const response = await client(`/api/accounts/${id}`, {
    method: 'GET',
  })

  if (!response.ok) return Promise.reject('Failed to get account')

  return await response.json()
}

export async function postAccount(values: AccountParams): Promise<Response | ApiErrorResponse> {
  const response = await client('/api/accounts', {
    method: 'POST',
    body: JSON.stringify(values),
  })

  if (!response.ok) return Promise.reject('Failed to create account')

  return await response.json()
}

export async function updateAccountId(
  id: string,
  values: AccountParams,
): Promise<Response | ApiErrorResponse> {
  const response = await client(`/api/accounts/${id}`, {
    method: 'PATCH',
    body: JSON.stringify(values),
  })

  if (!response.ok) return Promise.reject('Failed to update account')

  return await response.json()
}

export async function deleteAccountId(id: string): Promise<Response | ApiErrorResponse> {
  const response = await client(`/api/accounts/${id}`, {
    method: 'DELETE',
  })

  if (!response.ok) return Promise.reject('Failed to delete account')

  return response
}
