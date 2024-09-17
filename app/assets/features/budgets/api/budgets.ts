import { readLocalStorageValue } from '@mantine/hooks'

import { Budget, BudgetDetails, BudgetParams } from '@/features/budgets/types'

import { ApiErrorResponse } from '@/utils/ApiErrorResponse'
import { ApiResponse, ApiResponseList } from '@/utils/ApiResponse'

export async function getBudgetList(): Promise<ApiResponseList<Budget[]>> {
  const token = readLocalStorageValue({ key: 'token' }) as string | null

  const response = await fetch('/api/budgets', {
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

export async function postBudget(values: BudgetParams): Promise<Response | ApiErrorResponse> {
  const token = readLocalStorageValue({ key: 'token' }) as string | null

  const response = await fetch('/api/budgets', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify({
      date: values.date,
      incomes: values.incomes,
      expenses: values.expenses,
    }),
  })

  if (!response.ok) return Promise.reject('Failed to create budget')

  return await response.json()
}

export async function deleteBudgetId(id: string): Promise<Response | ApiErrorResponse> {
  const token = readLocalStorageValue({ key: 'token' }) as string | null

  const response = await fetch(`/api/budgets/${id}`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
  })

  if (!response.ok) return Promise.reject('Failed to delete budget')

  return response
}

export async function updateBudgetId(
  id: string,
  values: BudgetParams,
): Promise<Response | ApiErrorResponse> {
  const token = readLocalStorageValue({ key: 'token' }) as string | null

  const response = await fetch(`/api/budgets/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify({
      date: values.date,
      incomes: values.incomes,
      expenses: values.expenses,
    }),
  })

  if (!response.ok) return Promise.reject('Failed to update budget')

  return await response.json()
}
