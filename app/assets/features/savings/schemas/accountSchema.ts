import { z } from 'zod'

export const accountFormSchema = z.object({
  id: z.number().optional(),
  name: z.string().min(1, 'Le nom est requis'),
})

export type createAccountFormType = z.infer<typeof accountFormSchema>
