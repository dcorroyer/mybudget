import { z } from 'zod'

export const budgetFormSchema = z.object({
  date: z.date({
    errorMap: () => ({
      message: 'Date is required',
    }),
  }),
  incomes: z.array(
    z.object({
      id: z.number().optional(),
      name: z.string().min(2, 'Name is required'),
      amount: z.coerce.number().gt(0.01, { message: 'Amount must be greater than 0' }),
    }),
  ),
  expenses: z.array(
    z.object({
      category: z.string().min(2, 'Category is required'),
      items: z.array(
        z.object({
          id: z.number().optional(),
          name: z.string().min(2, 'Name is required'),
          amount: z.coerce.number().gt(0.01, { message: 'Amount must be greater than 0' }),
        }),
      ),
    }),
  ),
})

export type createBudgetFormType = z.infer<typeof budgetFormSchema>
