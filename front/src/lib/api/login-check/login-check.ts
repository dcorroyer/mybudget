/**
 * Generated by orval v7.5.0 🍺
 * Do not edit manually.
 * Hello API Platform
 * OpenAPI spec version: 1.0.0
 */
import { useMutation } from '@tanstack/react-query'
import type { MutationFunction, UseMutationOptions, UseMutationResult } from '@tanstack/react-query'
import type { LoginCheckPost200, LoginCheckPostBody } from '.././models'
import { customInstance } from '.././axios-instance'
import type { ErrorType } from '.././axios-instance'

type SecondParameter<T extends (...args: any) => any> = Parameters<T>[1]

/**
 * Creates a user token.
 * @summary Creates a user token.
 */
export const loginCheckPost = (
  loginCheckPostBody: LoginCheckPostBody,
  options?: SecondParameter<typeof customInstance>,
  signal?: AbortSignal,
) => {
  return customInstance<LoginCheckPost200>(
    {
      url: `https://myfitness.api.localhost/auth`,
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      data: loginCheckPostBody,
      signal,
    },
    options,
  )
}

export const getLoginCheckPostMutationOptions = <
  TError = ErrorType<unknown>,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof loginCheckPost>>,
    TError,
    { data: LoginCheckPostBody },
    TContext
  >
  request?: SecondParameter<typeof customInstance>
}): UseMutationOptions<
  Awaited<ReturnType<typeof loginCheckPost>>,
  TError,
  { data: LoginCheckPostBody },
  TContext
> => {
  const mutationKey = ['loginCheckPost']
  const { mutation: mutationOptions, request: requestOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey }, request: undefined }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof loginCheckPost>>,
    { data: LoginCheckPostBody }
  > = (props) => {
    const { data } = props ?? {}

    return loginCheckPost(data, requestOptions)
  }

  return { mutationFn, ...mutationOptions }
}

export type LoginCheckPostMutationResult = NonNullable<Awaited<ReturnType<typeof loginCheckPost>>>
export type LoginCheckPostMutationBody = LoginCheckPostBody
export type LoginCheckPostMutationError = ErrorType<unknown>

/**
 * @summary Creates a user token.
 */
export const useLoginCheckPost = <TError = ErrorType<unknown>, TContext = unknown>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof loginCheckPost>>,
    TError,
    { data: LoginCheckPostBody },
    TContext
  >
  request?: SecondParameter<typeof customInstance>
}): UseMutationResult<
  Awaited<ReturnType<typeof loginCheckPost>>,
  TError,
  { data: LoginCheckPostBody },
  TContext
> => {
  const mutationOptions = getLoginCheckPostMutationOptions(options)

  return useMutation(mutationOptions)
}
