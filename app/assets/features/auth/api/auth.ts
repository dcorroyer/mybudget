import { LoginParams, RegisterParams, User } from '@/features/auth/types'
import { ApiErrorResponse } from '@/utils/ApiErrorResponse'
import { ApiResponse } from '@/utils/ApiResponse'
import { client } from '@/utils/client'

export async function postLogin(values: LoginParams): Promise<Response | ApiErrorResponse> {
  const response = await client('/api/login', {
    method: 'POST',
    body: JSON.stringify({
      username: values.email,
      password: values.password,
    }),
  })

  if (!response.ok) return Promise.reject('Failed to login')

  return response.json()
}

export async function postRegister(values: RegisterParams): Promise<Response | ApiErrorResponse> {
  const response = await client('/api/register', {
    method: 'POST',
    body: JSON.stringify({
      firstName: values.firstName,
      lastName: values.lastName,
      email: values.email,
      password: values.password,
    }),
  })

  if (!response.ok) return Promise.reject('Failed to register')

  return response.json()
}

export const getMe = async (): Promise<ApiResponse<User>> => {
  const response = await client('/api/users/me', {
    method: 'GET',
  })

  if (!response.ok) return Promise.reject('Failed to get current user')

  return response.json()
}
