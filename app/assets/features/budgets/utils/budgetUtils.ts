import { ExpenseResponse } from '@/api/models'
import { GroupedExpenses } from '../types/budgetTypes'

export const formatDateToYYYYMM = (date: Date): string => {
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  return `${year}-${month}`
}

export const parseDateFromYYYYMM = (dateStr: string): Date => {
  const [year, month] = dateStr.split('-').map(Number)
  return new Date(year, month - 1)
}

export const flattenGroupedExpenses = (groupedExpenses: GroupedExpenses[]): ExpenseResponse[] => {
  return groupedExpenses.flatMap((group) =>
    group.items.map((item) => ({
      ...item,
      category: group.category,
    })),
  )
}

export const generateSankeyData = (expenses: GroupedExpenses[]): (string | number)[][] => {
  const data: (string | number)[][] = [['From', 'To', 'Weight']]

  expenses.forEach((expenseCategory) => {
    const categoryTotal = expenseCategory.items.reduce((sum, item) => sum + item.amount, 0)
    data.push(['Total Dépenses', expenseCategory.category, categoryTotal])

    expenseCategory.items.forEach((item) => {
      data.push([expenseCategory.category, item.name, item.amount])
    })
  })

  return data
}

export const formatAmount = (amount: number = 0) => amount.toLocaleString('fr-FR')

export const calculatePercentage = (amount: number, total: number) =>
  total > 0 ? Math.round((amount / total) * 100) : 0

export const calculateCategoryTotal = (items: Array<{ amount: number }>) =>
  items.reduce((sum, item) => sum + (item.amount || 0), 0)
