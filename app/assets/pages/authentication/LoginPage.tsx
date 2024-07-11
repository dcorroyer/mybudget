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

import { useAuth } from '@/hooks/AuthProvider'
import { loginFormSchema, loginFormType } from '@/schemas/login'

import { login } from '@/api'

import classes from './LoginPage.module.css'

export default function LoginPage() {
  const navigate = useNavigate()
  const { setToken } = useAuth()

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
      notifications.show({
        title: 'Logged in',
        message: 'You have successfully logged in.',
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
        Login Page
      </Title>
      <Text c='dimmed' size='sm' ta='center' mt={5}>
        Do not have an account yet?{' '}
        <Anchor
          size='sm'
          component='button'
          onClick={() => {
            navigate('/register')
          }}
        >
          Create account
        </Anchor>
      </Text>

      <form onSubmit={loginForm.handleSubmit(onSubmit)}>
        <Paper withBorder shadow='md' p={30} mt={30} radius='md'>
          <TextInput
            label='Email'
            placeholder='john.doeuf@mybudget.fr'
            required
            {...loginForm.register('email')}
          />
          <PasswordInput
            label='Password'
            placeholder='Your password'
            required
            mt='md'
            {...loginForm.register('password')}
          />
          <Button type='submit' fullWidth mt='xl'>
            Sign in
          </Button>
        </Paper>
      </form>
    </Container>
  )
}
