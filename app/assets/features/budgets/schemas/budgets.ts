import { z } from 'zod'

export const incomesFormSchema = z.object({
  id: z.number().optional(),
  name: z.string().min(2, 'Name is required'),
  amount: z.coerce.number().gt(0, 'Amount is required'),
})

export const expenseItemSchema = z.object({
  id: z.number().optional(),
  name: z.string().min(2, 'Name is required'),
  amount: z.coerce.number().gt(0, 'Amount is required'),
})

export const expensesFormSchema = z.object({
  category: z.string().min(2, 'Category is required'),
  items: z.array(expenseItemSchema),
})

export const budgetFormSchema = z.object({
  date: z.date({
    errorMap: () => ({
      message: 'Date is required',
    }),
  }),
  incomes: z.array(incomesFormSchema),
  expenses: z.array(expensesFormSchema),
})

export type createBudgetFormType = z.infer<typeof budgetFormSchema>
