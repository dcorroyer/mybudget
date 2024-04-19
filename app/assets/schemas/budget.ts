import { z } from 'zod'

export const incomesFormSchema = z.object({
    name: z.string().min(2),
    amount: z.coerce.number().gt(0),
})

export const expenseLinesFormSchema = z.object({
    name: z.string().min(2),
    amount: z.coerce.number().gt(0),
})

export const expensesFormSchema = z.object({
    category: z.object({
        name: z.string().min(2),
    }),
    expenseLines: z.array(expenseLinesFormSchema),
})

export const budgetFormSchema = z.object({
    incomes: z.array(incomesFormSchema),
    expenses: z.array(expensesFormSchema),
})

export type formTypeCreateBudget = z.infer<typeof budgetFormSchema>
