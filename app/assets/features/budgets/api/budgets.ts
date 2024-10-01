import { Budget, BudgetDetails, BudgetParams } from '@/features/budgets/types'

import { ApiErrorResponse } from '@/utils/ApiErrorResponse'
import { ApiResponse, ApiResponseList } from '@/utils/ApiResponse'

export async function getBudgetList(): Promise<ApiResponseList<Budget[]>> {
  const response = await client('/api/budgets', {
    method: 'GET',
  })

  if (!response.ok) return Promise.reject('Failed to get user budgets')

  return await response.json()
}

export async function getBudgetDetail(id: string): Promise<ApiResponse<BudgetDetails>> {
  const response = await client(`/api/budgets/${id}`, {
    method: 'GET',
  })

  if (!response.ok) return Promise.reject('Failed to get user budgets')

  return await response.json()
}

export async function postBudget(values: BudgetParams): Promise<Response | ApiErrorResponse> {
  const response = await client('/api/budgets', {
    method: 'POST',
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
  const response = await client(`/api/budgets/${id}`, {
    method: 'DELETE',
  })

  if (!response.ok) return Promise.reject('Failed to delete budget')

  return response
}

export async function updateBudgetId(
  id: string,
  values: BudgetParams,
): Promise<Response | ApiErrorResponse> {
  const response = await client(`/api/budgets/${id}`, {
    method: 'PUT',
    body: JSON.stringify({
      date: values.date,
      incomes: values.incomes,
      expenses: values.expenses,
    }),
  })

  if (!response.ok) return Promise.reject('Failed to update budget')

  return await response.json()
}
