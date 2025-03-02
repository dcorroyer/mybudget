import { useForm } from '@mantine/form'
import React from 'react'
import { Link, useNavigate } from 'react-router-dom'

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
import { notifications } from '@mantine/notifications'

import { usePostApiLogin } from '@/api/generated/authentication/authentication'
import { loginFormSchema, loginFormType } from '@/features/auth/schemas/login'

const Login: React.FC = () => {
  const navigate = useNavigate()

  const form = useForm<loginFormType>({
    initialValues: {
      email: '',
      password: '',
    },
    validate: zodResolver(loginFormSchema),
  })

  const { mutate: login, isPending: isLoading } = usePostApiLogin({
    mutation: {
      onSuccess: (data) => {
        if (data.token) {
          localStorage.setItem('token', data.token)

          notifications.show({
            title: 'Connexion réussie',
            message: 'Vous êtes maintenant connecté',
            color: 'green',
          })

          navigate('/')
        }
      },
      onError: (error: any) => {
        if (error.status === 401) {
          notifications.show({
            title: 'Erreur de connexion',
            message: error.message || 'Identifiants invalides',
            color: 'red',
          })
        } else {
          notifications.show({
            title: 'Erreur',
            message: 'Une erreur inattendue est survenue',
            color: 'red',
          })
        }
      },
    },
  })

  const onSubmit = (values: loginFormType) => {
    login({
      data: {
        username: values.email,
        password: values.password,
      },
    })
  }

  return (
    <Container size={420} my={40}>
      <Title ta='center' fw={900}>
        Login Page
      </Title>
      <Text c='dimmed' size='sm' ta='center' mt={5}>
        Do not have an account yet?{' '}
        <Anchor size='sm' component={Link} to='/auth/register'>
          Create account
        </Anchor>
      </Text>

      <form onSubmit={form.onSubmit(onSubmit)}>
        <Paper withBorder shadow='md' p={30} mt={30} radius='md'>
          <TextInput
            label='Email'
            placeholder='john.doeuf@mybudget.fr'
            required
            {...form.getInputProps('email')}
          />
          <PasswordInput
            label='Password'
            placeholder='Your password'
            required
            mt='md'
            {...form.getInputProps('password')}
          />
          <Button type='submit' fullWidth mt='xl' loading={isLoading}>
            Sign in
          </Button>
        </Paper>
      </form>
    </Container>
  )
}

export default Login
