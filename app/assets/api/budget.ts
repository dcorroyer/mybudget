import { readLocalStorageValue } from '@mantine/hooks'

export async function getBudgetList(): Promise<Response> {
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
