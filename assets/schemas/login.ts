import { z } from 'zod'

export const loginFormSchema = z.object({
    email: z.string().email(),
    password: z.string().min(2, {
        message: 'Password must be at least 2 characters.',
    }),
})

export type loginFormType = z.infer<typeof loginFormSchema>
