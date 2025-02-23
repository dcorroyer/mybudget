import { TextInput, PasswordInput, Button, Stack } from '@mantine/core'
import { useForm, zodResolver } from '@mantine/form'
import { RegisterInput, registerSchema } from './schemas'

interface RegisterFormProps {
  onSubmit: (values: RegisterInput) => void
}

export function RegisterForm({ onSubmit }: RegisterFormProps) {
  const form = useForm<RegisterInput>({
    initialValues: {
      email: '',
      password: '',
      confirmPassword: '',
    },
    validate: zodResolver(registerSchema),
  })

  return (
    <form onSubmit={form.onSubmit(onSubmit)}>
      <Stack>
        <TextInput
          required
          label='Email'
          placeholder='your@email.com'
          {...form.getInputProps('email')}
        />
        <PasswordInput
          required
          label='Password'
          placeholder='Your password'
          {...form.getInputProps('password')}
        />
        <PasswordInput
          required
          label='Confirm Password'
          placeholder='Confirm your password'
          {...form.getInputProps('confirmPassword')}
        />
        <Button type='submit'>Register</Button>
      </Stack>
    </form>
  )
}
