import { ExpenseResponse } from '@/api/models'
import { GroupedExpenses } from '../types/budgetTypes'
import { z } from 'zod'

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

export interface ExpenseCategory {
  id: string
  name: string
  items: Array<{ id: string; name: string; amount: number }>
}

export const formatZodErrors = (error: z.ZodError, categories: ExpenseCategory[]): string[] => {
  return error.errors.map((err) => {
    const fieldPath = err.path.join('.')
    let fieldName = ''

    if (fieldPath.startsWith('incomes')) {
      const matches = fieldPath.match(/incomes\.(\d+)\.(.+)/)
      if (matches) {
        const [, index, field] = matches
        fieldName = `Revenu #${Number(index) + 1} - ${field === 'name' ? 'Nom' : 'Montant'}`
      }
    } else if (fieldPath.startsWith('expenses')) {
      const matches = fieldPath.match(/expenses\.(\d+)\.(.+)/)
      if (matches) {
        const [, index, field] = matches
        const expenseIndex = Number(index)

        let categoryName = ''
        let expenseCounter = 0
        let innerExpenseIndex = 0

        for (const category of categories) {
          if (expenseCounter + category.items.length > expenseIndex) {
            categoryName = category.name || 'Catégorie sans nom'
            innerExpenseIndex = expenseIndex - expenseCounter
            break
          }
          expenseCounter += category.items.length
        }

        if (field === 'category') {
          fieldName = `Catégorie "${categoryName}"`
        } else {
          fieldName = `Dépense #${innerExpenseIndex + 1} (Catégorie "${categoryName}") - ${field === 'name' ? 'Nom' : 'Montant'}`
        }
      }
    } else if (fieldPath === 'date') {
      fieldName = 'Date'
    }

    return `• ${fieldName}: ${err.message}`
  })
}
