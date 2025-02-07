import { z } from 'zod'

export const accountFormSchema = z.object({
  id: z.number().optional(),
  name: z.string().min(2, 'Name is required'),
})

export type createAccountFormType = z.infer<typeof accountFormSchema>
