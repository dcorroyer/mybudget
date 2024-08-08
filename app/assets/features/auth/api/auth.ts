import { readLocalStorageValue } from '@mantine/hooks'

import { LoginParams, RegisterParams, User } from '@/features/auth/types'
import { ApiErrorResponse } from '@/utils/ApiErrorResponse'
import { ApiResponse } from '@/utils/ApiResponse'

export async function postLogin(values: LoginParams): Promise<Response | ApiErrorResponse> {
  const response = await fetch('/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      username: values.email,
      password: values.password,
    }),
  })

  if (!response.ok) return Promise.reject('Failed to login')

  return await response.json()
}

export async function postRegister(values: RegisterParams): Promise<Response | ApiErrorResponse> {
  const response = await fetch('api/register', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      firstName: values.firstName,
      lastName: values.lastName,
      email: values.email,
      password: values.password,
    }),
  })

  if (!response.ok) return Promise.reject('Failed to register')

  return await response.json()
}

export async function getMe(): Promise<ApiResponse<User>> {
  const token = readLocalStorageValue({ key: 'token' }) as string | null

  const response = await fetch('api/users/me', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
  })

  if (!response.ok) return Promise.reject('Failed to get current user')

  return await response.json()
}
