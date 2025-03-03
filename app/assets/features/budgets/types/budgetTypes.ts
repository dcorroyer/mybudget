import { ExpenseResponse } from '@/api/models'

export interface GroupedExpenses {
  category: string
  items: ExpenseResponse[]
  total: number
}
