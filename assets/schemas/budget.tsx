import { z } from 'zod'

export const schemaIncomes = z.object({
    name: z.string().min(2),
    amount: z.coerce.number().gt(0),
})

export const schemaExpenseLines = z.object({
    name: z.string().min(2),
    amount: z.coerce.number().gt(0),
})

export const schemaExpenses = z.object({
    categoryName: z.string().min(2),
    expenseLines: z.array(schemaExpenseLines),
})

export const schemaCreateBudget = z.object({
    incomes: z.array(schemaIncomes),
    expenses: z.array(schemaExpenses),
})

export type FormTypeCreateBudget = z.infer<typeof schemaCreateBudget>
