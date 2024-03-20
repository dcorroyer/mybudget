import React from 'react'
import { MailIcon, UserIcon } from 'lucide-react'

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
import { useNavigate } from 'react-router-dom'
import { useToast } from '@/components/hooks/useToast'

import { registerFormSchema, registerFormType } from '@/schemas/register'
import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { register } from '@/api'

function RegisterPage(): React.JSX.Element {
    const navigate = useNavigate()
    const { toast } = useToast()

    const registerForm = useForm<registerFormType>({
        resolver: zodResolver(registerFormSchema),
        defaultValues: {
            firstName: '',
            lastName: '',
            email: '',
            password: '',
            repeatPassword: '',
        },
    })

    async function onSubmit(values: registerFormType): Promise<void> {
        try {
            const response = await register(values)

            if (!response.ok) {
                throw new Error('Failed to register')
            }

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
                    <Form {...registerForm}>
                        <form onSubmit={registerForm.handleSubmit(onSubmit)} className='space-y-2'>
                            <FormField
                                control={registerForm.control}
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
                                control={registerForm.control}
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
                                control={registerForm.control}
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
                                control={registerForm.control}
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
                                control={registerForm.control}
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

export default RegisterPage
