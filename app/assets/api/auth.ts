import { loginFormType, registerFormType } from '@/schemas'

export async function login(values: loginFormType): Promise<Response> {
  return await fetch('api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/ld+json',
    },
    body: JSON.stringify({
      username: values.email,
      password: values.password,
    }),
  })
}

export async function register(values: registerFormType): Promise<Response> {
  return await fetch('api/register', {
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
}
