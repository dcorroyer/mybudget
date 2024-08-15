import React from 'react'
import { useForm } from 'react-hook-form'

import { zodResolver } from '@hookform/resolvers/zod'

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

import { useAuth } from '@/features/auth/hooks/useAuth'
import { loginFormSchema, loginFormType } from '@/features/auth/schemas/login'

import { Link } from '@tanstack/react-router'
import classes from './login.module.css'

export const Login = () => {
  const { login } = useAuth()

  const loginForm = useForm<loginFormType>({
    resolver: zodResolver(loginFormSchema),
    defaultValues: {
      email: '',
      password: '',
    },
  })

  const onSubmit = (values: loginFormType) => {
    login(values.email, values.password)
  }

  return (
    <Container size={420} my={40}>
      <Title ta='center' className={classes.title}>
        Login Page
      </Title>
      <Text c='dimmed' size='sm' ta='center' mt={5}>
        Do not have an account yet?{' '}
        <Anchor size='sm' component={Link} to='/register'>
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
