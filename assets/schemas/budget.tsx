import React, { ReactNode } from 'react'
import { z } from 'zod'
import { FormProvider, useForm, useFormContext } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'

/**
 * Schemas
 */

export const schemaExpenseLines = z.object({
    name: z.string().min(2),
    amount: z.number().min(0),
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

export const useFormCreateBudget = () =>
    useForm<FormTypeCreateBudget>({
        resolver: zodResolver(schemaCreateBudget),
    })

/**
 * Context
 */

export const FormProviderCreateBudget = ({ children }: { children: ReactNode }) => {
    const methods = useFormCreateBudget()
    return <FormProvider {...methods}>{children}</FormProvider>
}

export const useFormContextCreateBudget = () => useFormContext<FormTypeCreateBudget>()
