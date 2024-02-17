import React from 'react'
import { zodResolver } from '@hookform/resolvers/zod'
import { useForm } from 'react-hook-form'
import { z } from 'zod'
import { MailIcon } from 'lucide-react'

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
import { useAuth } from '@/hooks/AuthProvider'

const formSchema = z.object({
    email: z.string().min(2, {
        message: 'Email must be at least 2 characters.',
    }),
    password: z.string().min(2, {
        message: 'Password must be at least 2 characters.',
    }),
})

function LoginForm(): React.JSX.Element {
    const form = useForm<z.infer<typeof formSchema>>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            email: '',
            password: '',
        },
    })

    const navigate = useNavigate()
    const { setToken } = useAuth()
    const { toast } = useToast()

    async function onSubmit(values: z.infer<typeof formSchema>): Promise<void> {
        try {
            const response = await fetch('api/login_check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    username: values.email,
                    password: values.password,
                }),
            })

            if (!response.ok) {
                throw new Error('Failed to login')
            }

            const token = await response.text()
            setToken(token)

            navigate('/dashboard')
            toast({
                title: 'Logged in',
                description: 'You have successfully logged in.',
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
                    <CardTitle>Login page</CardTitle>
                    <CardDescription>
                        Use your credentials to login to your account.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Form {...form}>
                        <form onSubmit={form.handleSubmit(onSubmit)} className='space-y-2'>
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
                            <Button type='submit'>Login</Button>
                        </form>
                    </Form>
                </CardContent>
                <CardFooter>
                    <Button
                        className='mx-auto'
                        variant='ghost'
                        onClick={() => navigate('/register')}
                    >
                        Not yet registered? Create an account here
                    </Button>
                </CardFooter>
            </Card>
        </div>
    )
}

export default LoginForm
