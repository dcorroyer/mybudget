import { z } from 'zod'

export const budgetFormSchema = z.object({
  date: z.date(),
  incomes: z.array(
    z.object({
      id: z.number().optional(),
      name: z.string().min(1, 'Le nom est requis'),
      amount: z.number().min(0, 'Le montant doit être positif'),
    }),
  ),
  expenses: z.array(
    z.object({
      id: z.number().optional(),
      name: z.string().min(1, 'Le nom est requis'),
      amount: z.number().min(0, 'Le montant doit être positif'),
      category: z.string().min(1, 'La catégorie est requise'),
    }),
  ),
})

export type BudgetFormData = z.infer<typeof budgetFormSchema>
