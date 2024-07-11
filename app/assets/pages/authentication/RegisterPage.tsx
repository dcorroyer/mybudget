import React from 'react'
import { useNavigate } from 'react-router-dom'

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

import classes from './RegisterPage.module.css'

export default function RegisterPage() {
  const navigate = useNavigate()

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

      <Paper withBorder shadow='md' p={30} mt={30} radius='md'>
        <TextInput label='Lastname' placeholder='Doe' required />
        <TextInput label='Firstname' placeholder='John' required mt='md' />
        <TextInput label='Email' placeholder='john.doeuf@mybudget.fr' required mt='md' />
        <PasswordInput label='Password' placeholder='Your password' required mt='md' />
        <PasswordInput
          label='Repeat-password'
          placeholder='Repeat your password'
          required
          mt='md'
        />
        <Button fullWidth mt='xl'>
          Register
        </Button>
      </Paper>
    </Container>
  )
}
