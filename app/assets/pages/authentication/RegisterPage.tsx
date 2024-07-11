import React from 'react'
import { useForm } from 'react-hook-form'
import { useNavigate } from 'react-router-dom'

import { zodResolver } from '@hookform/resolvers/zod'

import { notifications } from '@mantine/notifications'

import {
  Anchor,
  Button,
  Container,
  Paper,
  PasswordInput,
  Text,
  TextInput,
  Title,
} from '@mantine/core'

import { register } from '@/api'
import { registerFormSchema, registerFormType } from '@/schemas/register'

import classes from './RegisterPage.module.css'

export default function RegisterPage() {
  const navigate = useNavigate()

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
      notifications.show({
        title: 'Registered successfully',
        message: 'You have successfully registered in.',
      })
    } catch (error) {
      console.log('Error logging in:', error)

      notifications.show({
        title: 'Something went wrong ...',
        message: 'You have to try again.',
      })
    }
  }

  return (
    <Container size={420} my={40}>
      <Title ta='center' className={classes.title}>
        Register Page
      </Title>
      <Text c='dimmed' size='sm' ta='center' mt={5}>
        Already have an account?{' '}
        <Anchor
          size='sm'
          component='button'
          onClick={() => {
            navigate('/login')
          }}
        >
          Sign in
        </Anchor>
      </Text>

      <form onSubmit={registerForm.handleSubmit(onSubmit)}>
        <Paper withBorder shadow='md' p={30} mt={30} radius='md'>
          <TextInput
            label='Lastname'
            placeholder='Doe'
            required
            {...registerForm.register('lastName')}
          />
          <TextInput
            label='Firstname'
            placeholder='John'
            required
            mt='md'
            {...registerForm.register('firstName')}
          />
          <TextInput
            label='Email'
            placeholder='john.doeuf@mybudget.fr'
            required
            mt='md'
            {...registerForm.register('email')}
          />
          <PasswordInput
            label='Password'
            placeholder='Your password'
            required
            mt='md'
            {...registerForm.register('password')}
          />
          <PasswordInput
            label='Repeat-password'
            placeholder='Repeat your password'
            required
            mt='md'
            {...registerForm.register('repeatPassword')}
          />
          <Button type='submit' fullWidth mt='xl'>
            Register
          </Button>
        </Paper>
      </form>
    </Container>
  )
}
