import React from 'react'
import { useNavigate } from 'react-router-dom'
import { zodResolver } from '@hookform/resolvers/zod'
import { useForm } from 'react-hook-form'
import { MailIcon } from 'lucide-react'

import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form'

import { Input } from '@/components/ui/input'
import { PasswordInput } from '@/components/ui/password-input'

import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'

import { Button } from '@/components/ui/button'
import { useToast } from '@/components/hooks/useToast'

import { useAuth } from '@/hooks/AuthProvider'
import { loginFormSchema, loginFormType } from '@/schemas/login'
import { login } from '@/api'

function LoginPage(): React.JSX.Element {
    const navigate = useNavigate()
    const { setToken } = useAuth()
    const { toast } = useToast()

    const loginForm = useForm<loginFormType>({
        resolver: zodResolver(loginFormSchema),
        defaultValues: {
            email: '',
            password: '',
        },
    })

    async function onSubmit(values: loginFormType): Promise<void> {
        try {
            const response = await login(values)

            if (!response.ok) {
                throw new Error('Failed to login')
            }

            const token = await response.text()
            setToken(token)

            navigate('/')
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
                    <Form {...loginForm}>
                        <form onSubmit={loginForm.handleSubmit(onSubmit)} className='space-y-2'>
                            <FormField
                                control={loginForm.control}
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
                                control={loginForm.control}
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

export default LoginPage
