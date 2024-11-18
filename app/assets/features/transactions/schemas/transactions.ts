import { z } from 'zod'

export const transactionFormSchema = z.object({
  description: z.string().min(2, 'Description is required'),
  amount: z.number().gt(0.01, { message: 'Amount must be greater than 0' }),
  type: z.enum(['CREDIT', 'DEBIT']),
  date: z.date(),
  account: z.object({
    id: z.number(),
    name: z.string(),
  }),
})

export type createTransactionFormType = z.infer<typeof transactionFormSchema>
