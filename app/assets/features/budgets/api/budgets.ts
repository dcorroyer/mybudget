import { ApiResponse, ApiResponseList } from '@/utils/ApiResponse'
import { readLocalStorageValue } from '@mantine/hooks'

import { createBudgetFormType } from '@/features/budgets/schemas'
import { Budget, BudgetDetails, BudgetParams } from '@/features/budgets/types'

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

export async function postBudget(values: createBudgetFormType): Promise<ApiResponse<BudgetParams>> {
  const token = readLocalStorageValue({ key: 'token' }) as string | null

  const response = await fetch('api/budgets', {
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
