import { z } from 'zod'

export const budgetFormSchema = z.object({
  date: z.date(),
  incomes: z.array(
    z.object({
      id: z.number().optional(),
      name: z.string().min(1, 'Le nom est requis'),
      amount: z
        .number()
        .min(0, 'Le montant doit être positif')
        .max(1000000, 'Le montant doit être inférieur à 1 000 000'),
    }),
  ),
  expenses: z.array(
    z.object({
      id: z.number().optional(),
      name: z.string().min(1, 'Le nom est requis'),
      amount: z
        .number()
        .min(0, 'Le montant doit être positif')
        .max(1000000, 'Le montant doit être inférieur à 1 000 000'),
      category: z.string().min(1, 'La catégorie est requise'),
    }),
  ),
})

export type BudgetFormData = z.infer<typeof budgetFormSchema>
