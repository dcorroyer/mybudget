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

import { usePostApiLogin, usePostApiRegister } from '@/api/generated/authentication/authentication'
import { PostApiRegister400 } from '@/api/models'
import { registerFormSchema, registerFormType } from '@/features/auth/schemas/RegisterSchema'

const Register: React.FC = () => {
  const navigate = useNavigate()

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

  const { mutate: login } = usePostApiLogin({
    mutation: {
      onSuccess: (data) => {
        if (data.token) {
          localStorage.setItem('token', data.token)
          navigate('/')
        }
      },
      onError: (error: any) => {
        notifications.show({
          title: 'Connexion automatique échouée',
          message: error.message || 'Une erreur est survenue lors de la connexion automatique',
          color: 'yellow',
        })
        navigate('/auth/login')
      },
    },
  })

  const { mutate: register, isPending: isRegistering } = usePostApiRegister({
    mutation: {
      onSuccess: () => {
        notifications.show({
          title: 'Inscription réussie',
          message: 'Votre compte a été créé avec succès, vous allez être connecté automatiquement',
          color: 'green',
        })

        login({
          data: {
            username: form.values.email,
            password: form.values.password,
          },
        })
      },
      onError: (error: PostApiRegister400) => {
        notifications.show({
          title: "Erreur d'inscription",
          message: error.message || "Une erreur est survenue lors de l'inscription",
          color: 'red',
        })
      },
    },
  })

  const onSubmit = (values: registerFormType) => {
    register({
      data: {
        firstName: values.firstName,
        lastName: values.lastName,
        email: values.email,
        password: values.password,
      },
    })
  }

  return (
    <Container size={460} my={40}>
      <Title ta='center' fw={900}>
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
          <Button type='submit' fullWidth mt='xl' loading={isRegistering}>
            Register
          </Button>
        </Paper>
      </form>
    </Container>
  )
}

export default Register
