/**
 * Generated by orval v7.6.0 🍺
 * Do not edit manually.
 * MyBudget API
 * API for budget and savings management
 * OpenAPI spec version: 1.0.0
 */
import { useInfiniteQuery, useMutation, useQuery } from '@tanstack/react-query'
import type {
  DataTag,
  DefinedInitialDataOptions,
  DefinedUseInfiniteQueryResult,
  DefinedUseQueryResult,
  InfiniteData,
  MutationFunction,
  QueryFunction,
  QueryKey,
  UndefinedInitialDataOptions,
  UseInfiniteQueryOptions,
  UseInfiniteQueryResult,
  UseMutationOptions,
  UseMutationResult,
  UseQueryOptions,
  UseQueryResult,
} from '@tanstack/react-query'

import type {
  DeleteApiTransactionsDelete404,
  GetApiTransactionsGet200,
  GetApiTransactionsGet404,
  GetApiTransactionsList200,
  GetApiTransactionsListParams,
  PostApiTransactionsCreate201,
  PostApiTransactionsCreate400,
  PostApiTransactionsCreate404,
  PutApiTransactionUpdate200,
  PutApiTransactionUpdate400,
  PutApiTransactionUpdate404,
  TransactionPayload,
} from '../../models'

import { customInstance } from '../../axiosInstance'

/**
 * Get the paginated list of transactions
 * @summary List transactions
 */
export const getApiTransactionsList = (
  params?: GetApiTransactionsListParams,
  signal?: AbortSignal,
) => {
  return customInstance<GetApiTransactionsList200>({
    url: `/api/accounts/transactions`,
    method: 'GET',
    params,
    signal,
  })
}

export const getGetApiTransactionsListQueryKey = (params?: GetApiTransactionsListParams) => {
  return [`/api/accounts/transactions`, ...(params ? [params] : [])] as const
}

export const getGetApiTransactionsListInfiniteQueryOptions = <
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiTransactionsList>>,
    GetApiTransactionsListParams['page']
  >,
  TError = unknown,
>(
  params?: GetApiTransactionsListParams,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        QueryKey,
        GetApiTransactionsListParams['page']
      >
    >
  },
) => {
  const { query: queryOptions } = options ?? {}

  const queryKey = queryOptions?.queryKey ?? getGetApiTransactionsListQueryKey(params)

  const queryFn: QueryFunction<
    Awaited<ReturnType<typeof getApiTransactionsList>>,
    QueryKey,
    GetApiTransactionsListParams['page']
  > = ({ signal, pageParam }) =>
    getApiTransactionsList({ ...params, page: pageParam || params?.['page'] }, signal)

  return { queryKey, queryFn, staleTime: 10000, ...queryOptions } as UseInfiniteQueryOptions<
    Awaited<ReturnType<typeof getApiTransactionsList>>,
    TError,
    TData,
    Awaited<ReturnType<typeof getApiTransactionsList>>,
    QueryKey,
    GetApiTransactionsListParams['page']
  > & { queryKey: DataTag<QueryKey, TData> }
}

export type GetApiTransactionsListInfiniteQueryResult = NonNullable<
  Awaited<ReturnType<typeof getApiTransactionsList>>
>
export type GetApiTransactionsListInfiniteQueryError = unknown

export function useGetApiTransactionsListInfinite<
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiTransactionsList>>,
    GetApiTransactionsListParams['page']
  >,
  TError = unknown,
>(
  params: undefined | GetApiTransactionsListParams,
  options: {
    query: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        QueryKey,
        GetApiTransactionsListParams['page']
      >
    > &
      Pick<
        DefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiTransactionsList>>,
          TError,
          Awaited<ReturnType<typeof getApiTransactionsList>>,
          QueryKey
        >,
        'initialData'
      >
  },
): DefinedUseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiTransactionsListInfinite<
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiTransactionsList>>,
    GetApiTransactionsListParams['page']
  >,
  TError = unknown,
>(
  params?: GetApiTransactionsListParams,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        QueryKey,
        GetApiTransactionsListParams['page']
      >
    > &
      Pick<
        UndefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiTransactionsList>>,
          TError,
          Awaited<ReturnType<typeof getApiTransactionsList>>,
          QueryKey
        >,
        'initialData'
      >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiTransactionsListInfinite<
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiTransactionsList>>,
    GetApiTransactionsListParams['page']
  >,
  TError = unknown,
>(
  params?: GetApiTransactionsListParams,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        QueryKey,
        GetApiTransactionsListParams['page']
      >
    >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
/**
 * @summary List transactions
 */

export function useGetApiTransactionsListInfinite<
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiTransactionsList>>,
    GetApiTransactionsListParams['page']
  >,
  TError = unknown,
>(
  params?: GetApiTransactionsListParams,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiTransactionsList>>,
        QueryKey,
        GetApiTransactionsListParams['page']
      >
    >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> } {
  const queryOptions = getGetApiTransactionsListInfiniteQueryOptions(params, options)

  const query = useInfiniteQuery(queryOptions) as UseInfiniteQueryResult<TData, TError> & {
    queryKey: DataTag<QueryKey, TData>
  }

  query.queryKey = queryOptions.queryKey

  return query
}

export const getGetApiTransactionsListQueryOptions = <
  TData = Awaited<ReturnType<typeof getApiTransactionsList>>,
  TError = unknown,
>(
  params?: GetApiTransactionsListParams,
  options?: {
    query?: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsList>>, TError, TData>
    >
  },
) => {
  const { query: queryOptions } = options ?? {}

  const queryKey = queryOptions?.queryKey ?? getGetApiTransactionsListQueryKey(params)

  const queryFn: QueryFunction<Awaited<ReturnType<typeof getApiTransactionsList>>> = ({ signal }) =>
    getApiTransactionsList(params, signal)

  return { queryKey, queryFn, staleTime: 10000, ...queryOptions } as UseQueryOptions<
    Awaited<ReturnType<typeof getApiTransactionsList>>,
    TError,
    TData
  > & { queryKey: DataTag<QueryKey, TData> }
}

export type GetApiTransactionsListQueryResult = NonNullable<
  Awaited<ReturnType<typeof getApiTransactionsList>>
>
export type GetApiTransactionsListQueryError = unknown

export function useGetApiTransactionsList<
  TData = Awaited<ReturnType<typeof getApiTransactionsList>>,
  TError = unknown,
>(
  params: undefined | GetApiTransactionsListParams,
  options: {
    query: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsList>>, TError, TData>
    > &
      Pick<
        DefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiTransactionsList>>,
          TError,
          Awaited<ReturnType<typeof getApiTransactionsList>>
        >,
        'initialData'
      >
  },
): DefinedUseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiTransactionsList<
  TData = Awaited<ReturnType<typeof getApiTransactionsList>>,
  TError = unknown,
>(
  params?: GetApiTransactionsListParams,
  options?: {
    query?: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsList>>, TError, TData>
    > &
      Pick<
        UndefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiTransactionsList>>,
          TError,
          Awaited<ReturnType<typeof getApiTransactionsList>>
        >,
        'initialData'
      >
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiTransactionsList<
  TData = Awaited<ReturnType<typeof getApiTransactionsList>>,
  TError = unknown,
>(
  params?: GetApiTransactionsListParams,
  options?: {
    query?: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsList>>, TError, TData>
    >
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
/**
 * @summary List transactions
 */

export function useGetApiTransactionsList<
  TData = Awaited<ReturnType<typeof getApiTransactionsList>>,
  TError = unknown,
>(
  params?: GetApiTransactionsListParams,
  options?: {
    query?: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsList>>, TError, TData>
    >
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> } {
  const queryOptions = getGetApiTransactionsListQueryOptions(params, options)

  const query = useQuery(queryOptions) as UseQueryResult<TData, TError> & {
    queryKey: DataTag<QueryKey, TData>
  }

  query.queryKey = queryOptions.queryKey

  return query
}

/**
 * Create a new transaction for a specific account
 * @summary Create a transaction
 */
export const postApiTransactionsCreate = (
  accountId: number,
  transactionPayload: TransactionPayload,
  signal?: AbortSignal,
) => {
  return customInstance<PostApiTransactionsCreate201>({
    url: `/api/accounts/${accountId}/transactions`,
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    data: transactionPayload,
    signal,
  })
}

export const getPostApiTransactionsCreateMutationOptions = <
  TError = PostApiTransactionsCreate400 | PostApiTransactionsCreate404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiTransactionsCreate>>,
    TError,
    { accountId: number; data: TransactionPayload },
    TContext
  >
}): UseMutationOptions<
  Awaited<ReturnType<typeof postApiTransactionsCreate>>,
  TError,
  { accountId: number; data: TransactionPayload },
  TContext
> => {
  const mutationKey = ['postApiTransactionsCreate']
  const { mutation: mutationOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey } }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof postApiTransactionsCreate>>,
    { accountId: number; data: TransactionPayload }
  > = (props) => {
    const { accountId, data } = props ?? {}

    return postApiTransactionsCreate(accountId, data)
  }

  return { mutationFn, ...mutationOptions }
}

export type PostApiTransactionsCreateMutationResult = NonNullable<
  Awaited<ReturnType<typeof postApiTransactionsCreate>>
>
export type PostApiTransactionsCreateMutationBody = TransactionPayload
export type PostApiTransactionsCreateMutationError =
  | PostApiTransactionsCreate400
  | PostApiTransactionsCreate404

/**
 * @summary Create a transaction
 */
export const usePostApiTransactionsCreate = <
  TError = PostApiTransactionsCreate400 | PostApiTransactionsCreate404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiTransactionsCreate>>,
    TError,
    { accountId: number; data: TransactionPayload },
    TContext
  >
}): UseMutationResult<
  Awaited<ReturnType<typeof postApiTransactionsCreate>>,
  TError,
  { accountId: number; data: TransactionPayload },
  TContext
> => {
  const mutationOptions = getPostApiTransactionsCreateMutationOptions(options)

  return useMutation(mutationOptions)
}
/**
 * Retrieve a transaction by its ID
 * @summary Get a transaction
 */
export const getApiTransactionsGet = (accountId: number, id: number, signal?: AbortSignal) => {
  return customInstance<GetApiTransactionsGet200>({
    url: `/api/accounts/${accountId}/transactions/${id}`,
    method: 'GET',
    signal,
  })
}

export const getGetApiTransactionsGetQueryKey = (accountId: number, id: number) => {
  return [`/api/accounts/${accountId}/transactions/${id}`] as const
}

export const getGetApiTransactionsGetInfiniteQueryOptions = <
  TData = InfiniteData<Awaited<ReturnType<typeof getApiTransactionsGet>>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    >
  },
) => {
  const { query: queryOptions } = options ?? {}

  const queryKey = queryOptions?.queryKey ?? getGetApiTransactionsGetQueryKey(accountId, id)

  const queryFn: QueryFunction<Awaited<ReturnType<typeof getApiTransactionsGet>>> = ({ signal }) =>
    getApiTransactionsGet(accountId, id, signal)

  return {
    queryKey,
    queryFn,
    enabled: !!(accountId && id),
    staleTime: 10000,
    ...queryOptions,
  } as UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData> & {
    queryKey: DataTag<QueryKey, TData>
  }
}

export type GetApiTransactionsGetInfiniteQueryResult = NonNullable<
  Awaited<ReturnType<typeof getApiTransactionsGet>>
>
export type GetApiTransactionsGetInfiniteQueryError = GetApiTransactionsGet404

export function useGetApiTransactionsGetInfinite<
  TData = InfiniteData<Awaited<ReturnType<typeof getApiTransactionsGet>>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options: {
    query: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    > &
      Pick<
        DefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiTransactionsGet>>,
          TError,
          Awaited<ReturnType<typeof getApiTransactionsGet>>
        >,
        'initialData'
      >
  },
): DefinedUseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiTransactionsGetInfinite<
  TData = InfiniteData<Awaited<ReturnType<typeof getApiTransactionsGet>>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    > &
      Pick<
        UndefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiTransactionsGet>>,
          TError,
          Awaited<ReturnType<typeof getApiTransactionsGet>>
        >,
        'initialData'
      >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiTransactionsGetInfinite<
  TData = InfiniteData<Awaited<ReturnType<typeof getApiTransactionsGet>>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
/**
 * @summary Get a transaction
 */

export function useGetApiTransactionsGetInfinite<
  TData = InfiniteData<Awaited<ReturnType<typeof getApiTransactionsGet>>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> } {
  const queryOptions = getGetApiTransactionsGetInfiniteQueryOptions(accountId, id, options)

  const query = useInfiniteQuery(queryOptions) as UseInfiniteQueryResult<TData, TError> & {
    queryKey: DataTag<QueryKey, TData>
  }

  query.queryKey = queryOptions.queryKey

  return query
}

export const getGetApiTransactionsGetQueryOptions = <
  TData = Awaited<ReturnType<typeof getApiTransactionsGet>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options?: {
    query?: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    >
  },
) => {
  const { query: queryOptions } = options ?? {}

  const queryKey = queryOptions?.queryKey ?? getGetApiTransactionsGetQueryKey(accountId, id)

  const queryFn: QueryFunction<Awaited<ReturnType<typeof getApiTransactionsGet>>> = ({ signal }) =>
    getApiTransactionsGet(accountId, id, signal)

  return {
    queryKey,
    queryFn,
    enabled: !!(accountId && id),
    staleTime: 10000,
    ...queryOptions,
  } as UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData> & {
    queryKey: DataTag<QueryKey, TData>
  }
}

export type GetApiTransactionsGetQueryResult = NonNullable<
  Awaited<ReturnType<typeof getApiTransactionsGet>>
>
export type GetApiTransactionsGetQueryError = GetApiTransactionsGet404

export function useGetApiTransactionsGet<
  TData = Awaited<ReturnType<typeof getApiTransactionsGet>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options: {
    query: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    > &
      Pick<
        DefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiTransactionsGet>>,
          TError,
          Awaited<ReturnType<typeof getApiTransactionsGet>>
        >,
        'initialData'
      >
  },
): DefinedUseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiTransactionsGet<
  TData = Awaited<ReturnType<typeof getApiTransactionsGet>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options?: {
    query?: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    > &
      Pick<
        UndefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiTransactionsGet>>,
          TError,
          Awaited<ReturnType<typeof getApiTransactionsGet>>
        >,
        'initialData'
      >
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiTransactionsGet<
  TData = Awaited<ReturnType<typeof getApiTransactionsGet>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options?: {
    query?: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    >
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
/**
 * @summary Get a transaction
 */

export function useGetApiTransactionsGet<
  TData = Awaited<ReturnType<typeof getApiTransactionsGet>>,
  TError = GetApiTransactionsGet404,
>(
  accountId: number,
  id: number,
  options?: {
    query?: Partial<
      UseQueryOptions<Awaited<ReturnType<typeof getApiTransactionsGet>>, TError, TData>
    >
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> } {
  const queryOptions = getGetApiTransactionsGetQueryOptions(accountId, id, options)

  const query = useQuery(queryOptions) as UseQueryResult<TData, TError> & {
    queryKey: DataTag<QueryKey, TData>
  }

  query.queryKey = queryOptions.queryKey

  return query
}

/**
 * Update an existing transaction
 * @summary Update a transaction
 */
export const putApiTransactionUpdate = (
  accountId: number,
  id: number,
  transactionPayload: TransactionPayload,
) => {
  return customInstance<PutApiTransactionUpdate200>({
    url: `/api/accounts/${accountId}/transactions/${id}`,
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    data: transactionPayload,
  })
}

export const getPutApiTransactionUpdateMutationOptions = <
  TError = PutApiTransactionUpdate400 | PutApiTransactionUpdate404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof putApiTransactionUpdate>>,
    TError,
    { accountId: number; id: number; data: TransactionPayload },
    TContext
  >
}): UseMutationOptions<
  Awaited<ReturnType<typeof putApiTransactionUpdate>>,
  TError,
  { accountId: number; id: number; data: TransactionPayload },
  TContext
> => {
  const mutationKey = ['putApiTransactionUpdate']
  const { mutation: mutationOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey } }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof putApiTransactionUpdate>>,
    { accountId: number; id: number; data: TransactionPayload }
  > = (props) => {
    const { accountId, id, data } = props ?? {}

    return putApiTransactionUpdate(accountId, id, data)
  }

  return { mutationFn, ...mutationOptions }
}

export type PutApiTransactionUpdateMutationResult = NonNullable<
  Awaited<ReturnType<typeof putApiTransactionUpdate>>
>
export type PutApiTransactionUpdateMutationBody = TransactionPayload
export type PutApiTransactionUpdateMutationError =
  | PutApiTransactionUpdate400
  | PutApiTransactionUpdate404

/**
 * @summary Update a transaction
 */
export const usePutApiTransactionUpdate = <
  TError = PutApiTransactionUpdate400 | PutApiTransactionUpdate404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof putApiTransactionUpdate>>,
    TError,
    { accountId: number; id: number; data: TransactionPayload },
    TContext
  >
}): UseMutationResult<
  Awaited<ReturnType<typeof putApiTransactionUpdate>>,
  TError,
  { accountId: number; id: number; data: TransactionPayload },
  TContext
> => {
  const mutationOptions = getPutApiTransactionUpdateMutationOptions(options)

  return useMutation(mutationOptions)
}
/**
 * Delete an existing transaction
 * @summary Delete a transaction
 */
export const deleteApiTransactionsDelete = (accountId: number, id: number) => {
  return customInstance<void>({
    url: `/api/accounts/${accountId}/transactions/${id}`,
    method: 'DELETE',
  })
}

export const getDeleteApiTransactionsDeleteMutationOptions = <
  TError = DeleteApiTransactionsDelete404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof deleteApiTransactionsDelete>>,
    TError,
    { accountId: number; id: number },
    TContext
  >
}): UseMutationOptions<
  Awaited<ReturnType<typeof deleteApiTransactionsDelete>>,
  TError,
  { accountId: number; id: number },
  TContext
> => {
  const mutationKey = ['deleteApiTransactionsDelete']
  const { mutation: mutationOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey } }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof deleteApiTransactionsDelete>>,
    { accountId: number; id: number }
  > = (props) => {
    const { accountId, id } = props ?? {}

    return deleteApiTransactionsDelete(accountId, id)
  }

  return { mutationFn, ...mutationOptions }
}

export type DeleteApiTransactionsDeleteMutationResult = NonNullable<
  Awaited<ReturnType<typeof deleteApiTransactionsDelete>>
>

export type DeleteApiTransactionsDeleteMutationError = DeleteApiTransactionsDelete404

/**
 * @summary Delete a transaction
 */
export const useDeleteApiTransactionsDelete = <
  TError = DeleteApiTransactionsDelete404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof deleteApiTransactionsDelete>>,
    TError,
    { accountId: number; id: number },
    TContext
  >
}): UseMutationResult<
  Awaited<ReturnType<typeof deleteApiTransactionsDelete>>,
  TError,
  { accountId: number; id: number },
  TContext
> => {
  const mutationOptions = getDeleteApiTransactionsDeleteMutationOptions(options)

  return useMutation(mutationOptions)
}
