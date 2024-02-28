import { z } from 'zod'

/**
 * Schemas
 */

export const schemaExpenseLines = z.object({
    name: z.string().min(2),
    amount: z.coerce.number()
})

export const schemaCategories = z.object({
    name: z.string().min(2),
    expenseLines: z.array(schemaExpenseLines),
})

export const schemaCreateBudget = z.object({
    categories: z.array(schemaCategories),
})

/**
 * Hooks
 */

export type FormTypeCreateBudget = z.infer<typeof schemaCreateBudget>
