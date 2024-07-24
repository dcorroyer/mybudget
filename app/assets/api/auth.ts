import { LoginParams, RegisterParams } from '@/types'

export async function postLogin(values: LoginParams): Promise<Response> {
  const response = await fetch('/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/ld+json',
    },
    body: JSON.stringify({
      username: values.email,
      password: values.password,
    }),
  })

  if (!response.ok) throw new Error('Failed to login')

  return await response.json()
}

export async function postRegister(values: RegisterParams): Promise<Response> {
  const response = await fetch('api/register', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/ld+json',
    },
    body: JSON.stringify({
      firstName: values.firstName,
      lastName: values.lastName,
      email: values.email,
      password: values.password,
    }),
  })

  if (!response.ok) throw new Error('Failed to login')

  return await response.json()
}
