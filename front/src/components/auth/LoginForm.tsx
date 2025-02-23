import { TextInput, Button, Stack } from '@mantine/core'
import { useForm, zodResolver } from '@mantine/form'
import { LoginInput, loginSchema } from './schemas'
import { useAuth } from '../../contexts/AuthContext'
import { useLoginCheckPost } from '../../lib/api/login-check/login-check'

export function LoginForm() {
  const { login } = useAuth()
  const mutate = useLoginCheckPost()
  const form = useForm<LoginInput>({
    initialValues: {
      email: 'admin@domain.tld',
      password: 'admin',
    },
    validate: zodResolver(loginSchema),
  })

  const handleSubmit = async (values: LoginInput) => {
    mutate.mutate(
      { data: values },
      {
        onSuccess: (data) => {
          login(data.token)
          window.location.reload()
        },
        onError: (error) => {
          console.error('Login failed:', error)
        },
      },
    )
  }

  return (
    <form onSubmit={form.onSubmit(handleSubmit)}>
      <Stack>
        <TextInput
          required
          label='Email'
          placeholder='your@email.com'
          disabled={mutate.isPending}
          {...form.getInputProps('email')}
        />
        <TextInput
          required
          type='password'
          label='Password'
          placeholder='Your password'
          disabled={mutate.isPending}
          {...form.getInputProps('password')}
        />
        <Button type='submit' loading={mutate.isPending}>
          Login
        </Button>
      </Stack>
    </form>
  )
}
