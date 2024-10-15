import { useForm } from '@mantine/form'
import React from 'react'
import { Link } from 'react-router-dom'

import { zodResolver } from 'mantine-form-zod-resolver'

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
import { registerFormSchema, registerFormType } from '@/features/auth/schemas/register'

import classes from './register.module.css'

const Register: React.FC = () => {
  const { register } = useAuth()

  const form = useForm<registerFormType>({
    initialValues: {
      firstName: '',
      lastName: '',
      email: '',
      password: '',
      repeatPassword: '',
    },
    validate: zodResolver(registerFormSchema),
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
        <Anchor size='sm' component={Link} to='/auth/login'>
          Sign in
        </Anchor>
      </Text>

      <form onSubmit={form.onSubmit(onSubmit)}>
        <Paper withBorder shadow='md' p={30} mt={30} radius='md'>
          <TextInput
            label='Lastname'
            placeholder='Doe'
            required
            {...form.getInputProps('lastName')}
          />
          <TextInput
            label='Firstname'
            placeholder='John'
            required
            mt='md'
            {...form.getInputProps('firstName')}
          />
          <TextInput
            label='Email'
            placeholder='john.doeuf@mybudget.fr'
            required
            mt='md'
            {...form.getInputProps('email')}
          />
          <PasswordInput
            label='Password'
            placeholder='Your password'
            required
            mt='md'
            {...form.getInputProps('password')}
          />
          <PasswordInput
            label='Repeat-password'
            placeholder='Repeat your password'
            required
            mt='md'
            {...form.getInputProps('repeatPassword')}
          />
          <Button type='submit' fullWidth mt='xl'>
            Register
          </Button>
        </Paper>
      </form>
    </Container>
  )
}

export default Register
