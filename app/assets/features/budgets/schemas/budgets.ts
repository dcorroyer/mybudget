import { z } from 'zod'

export const incomesFormSchema = z.object({
  name: z.string().min(2),
  amount: z.coerce.number().gt(0),
})

export const expenseItemSchema = z.object({
  name: z.string().min(2),
  amount: z.coerce.number().gt(0),
})

export const expensesFormSchema = z.object({
  category: z.string().min(2),
  items: z.array(expenseItemSchema),
})

const dateRegex = /^\d{4}-(0[1-9]|1[0-2])$/

export const budgetFormSchema = z.object({
  date: z.string().refine((val) => dateRegex.test(val), {
    message: 'Invalid date format. Expected format: YYYY-MM',
  }),
  incomes: z.array(incomesFormSchema),
  expenses: z.array(expensesFormSchema),
})

export type createBudgetFormType = z.infer<typeof budgetFormSchema>
