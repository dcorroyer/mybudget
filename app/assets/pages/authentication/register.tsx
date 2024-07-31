import React from 'react'
import { useForm } from 'react-hook-form'
import { useNavigate } from 'react-router-dom'

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

import { useAuth } from '@/hooks/useAuth'
import { registerFormSchema, registerFormType } from '@/schemas/register'

import classes from './register.module.css'

export default function Register() {
  const navigate = useNavigate()
  const { register } = useAuth()

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

  const onSubmit = (values: registerFormType) => {
    register(values.firstName, values.lastName, values.email, values.password)
  }

  return (
    <Container size={460} my={40}>
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
