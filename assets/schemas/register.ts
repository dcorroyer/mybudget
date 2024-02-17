import { z } from 'zod'

export const registerFormSchema = z
    .object({
        firstName: z.string().min(3),
        lastName: z.string().min(2),
        email: z.string().email(),
        password: z.string().min(2),
        repeatPassword: z.string().min(2),
    })
    .refine((data) => data.password === data.repeatPassword, {
        message: 'Passwords do not match',
        path: ['repeatPassword'],
    })
