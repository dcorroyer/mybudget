/**
 * Generated by orval v7.6.0 🍺
 * Do not edit manually.
 * MyBudget API
 * API for budget and savings management
 * OpenAPI spec version: 1.0.0
 */
import { useMutation } from '@tanstack/react-query'
import type { MutationFunction, UseMutationOptions, UseMutationResult } from '@tanstack/react-query'

import type {
  PostApiLogin200,
  PostApiLogin401,
  PostApiLoginBody,
  PostApiRegister201,
  PostApiRegister400,
  RegisterPayload,
} from '../../models'

import { customInstance } from '../../axiosInstance'

/**
 * Allows a user to authenticate and obtain a JWT token
 * @summary User authentication
 */
export const postApiLogin = (postApiLoginBody: PostApiLoginBody, signal?: AbortSignal) => {
  return customInstance<PostApiLogin200>({
    url: `/api/login`,
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    data: postApiLoginBody,
    signal,
  })
}

export const getPostApiLoginMutationOptions = <
  TError = PostApiLogin401,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiLogin>>,
    TError,
    { data: PostApiLoginBody },
    TContext
  >
}): UseMutationOptions<
  Awaited<ReturnType<typeof postApiLogin>>,
  TError,
  { data: PostApiLoginBody },
  TContext
> => {
  const mutationKey = ['postApiLogin']
  const { mutation: mutationOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey } }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof postApiLogin>>,
    { data: PostApiLoginBody }
  > = (props) => {
    const { data } = props ?? {}

    return postApiLogin(data)
  }

  return { mutationFn, ...mutationOptions }
}

export type PostApiLoginMutationResult = NonNullable<Awaited<ReturnType<typeof postApiLogin>>>
export type PostApiLoginMutationBody = PostApiLoginBody
export type PostApiLoginMutationError = PostApiLogin401

/**
 * @summary User authentication
 */
export const usePostApiLogin = <TError = PostApiLogin401, TContext = unknown>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiLogin>>,
    TError,
    { data: PostApiLoginBody },
    TContext
  >
}): UseMutationResult<
  Awaited<ReturnType<typeof postApiLogin>>,
  TError,
  { data: PostApiLoginBody },
  TContext
> => {
  const mutationOptions = getPostApiLoginMutationOptions(options)

  return useMutation(mutationOptions)
}
/**
 * Allows a user to create an account
 * @summary User registration
 */
export const postApiRegister = (registerPayload: RegisterPayload, signal?: AbortSignal) => {
  return customInstance<PostApiRegister201>({
    url: `/api/register`,
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    data: registerPayload,
    signal,
  })
}

export const getPostApiRegisterMutationOptions = <
  TError = PostApiRegister400,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiRegister>>,
    TError,
    { data: RegisterPayload },
    TContext
  >
}): UseMutationOptions<
  Awaited<ReturnType<typeof postApiRegister>>,
  TError,
  { data: RegisterPayload },
  TContext
> => {
  const mutationKey = ['postApiRegister']
  const { mutation: mutationOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey } }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof postApiRegister>>,
    { data: RegisterPayload }
  > = (props) => {
    const { data } = props ?? {}

    return postApiRegister(data)
  }

  return { mutationFn, ...mutationOptions }
}

export type PostApiRegisterMutationResult = NonNullable<Awaited<ReturnType<typeof postApiRegister>>>
export type PostApiRegisterMutationBody = RegisterPayload
export type PostApiRegisterMutationError = PostApiRegister400

/**
 * @summary User registration
 */
export const usePostApiRegister = <TError = PostApiRegister400, TContext = unknown>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiRegister>>,
    TError,
    { data: RegisterPayload },
    TContext
  >
}): UseMutationResult<
  Awaited<ReturnType<typeof postApiRegister>>,
  TError,
  { data: RegisterPayload },
  TContext
> => {
  const mutationOptions = getPostApiRegisterMutationOptions(options)

  return useMutation(mutationOptions)
}
