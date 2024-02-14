import React from 'react'
import { zodResolver } from '@hookform/resolvers/zod'
import { useForm } from 'react-hook-form'
import { z } from 'zod'
import { MailIcon, UserIcon } from 'lucide-react'

import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/forms/form'

import { Input } from '@/components/ui/forms/input'
import { PasswordInput } from '@/components/ui/forms/password-input'

import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'

import { Button } from '@/components/ui/button'
import { useNavigate } from 'react-router-dom'
import { useToast } from '@/components/ui/toasts/use-toast'

const formSchema = z
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

function RegisterForm(): React.JSX.Element {
    const form = useForm<z.infer<typeof formSchema>>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            firstName: '',
            lastName: '',
            email: '',
            password: '',
            repeatPassword: '',
        },
    })

    const navigate = useNavigate()
    const { toast } = useToast()

    async function onSubmit(values: z.infer<typeof formSchema>): Promise<void> {
        try {
            const response = await fetch('api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    firstName: values.firstName,
                    lastName: values.lastName,
                    email: values.email,
                    password: values.password,
                }),
            })

            if (!response.ok) {
                throw new Error('Failed to register')
            }

            console.log(await response.json())
            navigate('/login')
            toast({
                title: 'Registered successfully',
                description: 'You have successfully registered in.',
                variant: 'default',
            })
        } catch (error) {
            console.log('Error logging in:', error)

            toast({
                title: 'Something went wrong ...',
                variant: 'destructive',
            })
        }
    }

    return (
        <div className='flex flex-col items-center py-12 sm:px-6 lg:px-8'>
            <Card className='w-full max-w-md space-y-4'>
                <CardHeader>
                    <CardTitle>Register page</CardTitle>
                    <CardDescription>
                        Enter your credentials to create your account.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Form {...form}>
                        <form onSubmit={form.handleSubmit(onSubmit)} className='space-y-2'>
                            <FormField
                                control={form.control}
                                name='firstName'
                                render={({ field }) => (
                                    <FormItem>
                                        <FormLabel>Firstname</FormLabel>
                                        <FormControl>
                                            <Input
                                                placeholder='Firstname'
                                                {...field}
                                                type='text'
                                                suffix={<UserIcon />}
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />
                            <FormField
                                control={form.control}
                                name='lastName'
                                render={({ field }) => (
                                    <FormItem>
                                        <FormLabel>Lastname</FormLabel>
                                        <FormControl>
                                            <Input
                                                placeholder='Lastname'
                                                {...field}
                                                type='text'
                                                suffix={<UserIcon />}
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />
                            <FormField
                                control={form.control}
                                name='email'
                                render={({ field }) => (
                                    <FormItem>
                                        <FormLabel>Email</FormLabel>
                                        <FormControl>
                                            <Input
                                                placeholder='Email'
                                                {...field}
                                                type='email'
                                                suffix={<MailIcon />}
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />
                            <FormField
                                control={form.control}
                                name='password'
                                render={({ field }) => (
                                    <FormItem>
                                        <FormLabel>Password</FormLabel>
                                        <FormControl>
                                            <PasswordInput placeholder='Password' {...field} />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />
                            <FormField
                                control={form.control}
                                name='repeatPassword'
                                render={({ field }) => (
                                    <FormItem>
                                        <FormControl>
                                            <PasswordInput
                                                placeholder='Repeat Password'
                                                {...field}
                                            />
                                        </FormControl>
                                        <FormMessage />
                                    </FormItem>
                                )}
                            />
                            <Button type='submit'>Register</Button>
                        </form>
                    </Form>
                </CardContent>
                <CardFooter>
                    <Button className='mx-auto' variant='ghost' onClick={() => navigate('/login')}>
                        You already have an account? Login here
                    </Button>
                </CardFooter>
            </Card>
        </div>
    )
}

export default RegisterForm
