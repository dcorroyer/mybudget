import { Budget, BudgetDetails } from '@/features/budgets/types'
import { ApiResponse, ApiResponseList } from '@/utils/ApiResponse'
import { readLocalStorageValue } from '@mantine/hooks'

export async function getBudgetList(): Promise<ApiResponseList<Budget[]>> {
  const token = readLocalStorageValue({ key: 'token' }) as string | null

  const response = await fetch('api/budgets', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
  })

  if (!response.ok) return Promise.reject('Failed to get user budgets')

  return await response.json()
}

export async function getBudgetDetail(id: string): Promise<ApiResponse<BudgetDetails>> {
  const token = readLocalStorageValue({ key: 'token' }) as string | null

  const response = await fetch(`/api/budgets/${id}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
  })

  if (!response.ok) return Promise.reject('Failed to get user budgets')

  return await response.json()
}
