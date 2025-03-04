import { TransactionPayloadType } from '@/api/models'
import { z } from 'zod'

export const transactionFormSchema = z.object({
  description: z.string().min(1, 'La description est requise'),
  amount: z.number().gt(0.01, { message: 'Le montant doit être supérieur à 0' }),
  type: z.enum([TransactionPayloadType.CREDIT, TransactionPayloadType.DEBIT], {
    errorMap: () => ({ message: 'Le type de transaction est invalide' }),
  }),
  date: z.date({
    errorMap: () => ({ message: 'La date est requise et doit être valide' }),
  }),
  account: z.object({
    id: z.number({
      errorMap: () => ({ message: 'Veuillez sélectionner un compte' }),
    }),
    name: z.string(),
  }),
})

export type createTransactionFormType = z.infer<typeof transactionFormSchema>
