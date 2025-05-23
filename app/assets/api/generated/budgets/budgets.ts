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
  BudgetPayload,
  DeleteApiBudgetsDelete404,
  GetApiBudgetsGet200,
  GetApiBudgetsGet404,
  GetApiBudgetsList200,
  GetApiBudgetsListParams,
  PostApiBudgetsCreate201,
  PostApiBudgetsCreate400,
  PostApiBudgetsDuplicate201,
  PostApiBudgetsDuplicate404,
  PutApiBudgetsUpdate200,
  PutApiBudgetsUpdate400,
  PutApiBudgetsUpdate404,
} from '../../models'

import { customInstance } from '../../axiosInstance'

/**
 * Get a paginated list of budgets
 * @summary List budgets
 */
export const getApiBudgetsList = (params?: GetApiBudgetsListParams, signal?: AbortSignal) => {
  return customInstance<GetApiBudgetsList200>({
    url: `/api/budgets`,
    method: 'GET',
    params,
    signal,
  })
}

export const getGetApiBudgetsListQueryKey = (params?: GetApiBudgetsListParams) => {
  return [`/api/budgets`, ...(params ? [params] : [])] as const
}

export const getGetApiBudgetsListInfiniteQueryOptions = <
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiBudgetsList>>,
    GetApiBudgetsListParams['page']
  >,
  TError = unknown,
>(
  params?: GetApiBudgetsListParams,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        QueryKey,
        GetApiBudgetsListParams['page']
      >
    >
  },
) => {
  const { query: queryOptions } = options ?? {}

  const queryKey = queryOptions?.queryKey ?? getGetApiBudgetsListQueryKey(params)

  const queryFn: QueryFunction<
    Awaited<ReturnType<typeof getApiBudgetsList>>,
    QueryKey,
    GetApiBudgetsListParams['page']
  > = ({ signal, pageParam }) =>
    getApiBudgetsList({ ...params, page: pageParam || params?.['page'] }, signal)

  return { queryKey, queryFn, staleTime: 10000, ...queryOptions } as UseInfiniteQueryOptions<
    Awaited<ReturnType<typeof getApiBudgetsList>>,
    TError,
    TData,
    Awaited<ReturnType<typeof getApiBudgetsList>>,
    QueryKey,
    GetApiBudgetsListParams['page']
  > & { queryKey: DataTag<QueryKey, TData> }
}

export type GetApiBudgetsListInfiniteQueryResult = NonNullable<
  Awaited<ReturnType<typeof getApiBudgetsList>>
>
export type GetApiBudgetsListInfiniteQueryError = unknown

export function useGetApiBudgetsListInfinite<
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiBudgetsList>>,
    GetApiBudgetsListParams['page']
  >,
  TError = unknown,
>(
  params: undefined | GetApiBudgetsListParams,
  options: {
    query: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        QueryKey,
        GetApiBudgetsListParams['page']
      >
    > &
      Pick<
        DefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiBudgetsList>>,
          TError,
          Awaited<ReturnType<typeof getApiBudgetsList>>,
          QueryKey
        >,
        'initialData'
      >
  },
): DefinedUseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiBudgetsListInfinite<
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiBudgetsList>>,
    GetApiBudgetsListParams['page']
  >,
  TError = unknown,
>(
  params?: GetApiBudgetsListParams,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        QueryKey,
        GetApiBudgetsListParams['page']
      >
    > &
      Pick<
        UndefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiBudgetsList>>,
          TError,
          Awaited<ReturnType<typeof getApiBudgetsList>>,
          QueryKey
        >,
        'initialData'
      >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiBudgetsListInfinite<
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiBudgetsList>>,
    GetApiBudgetsListParams['page']
  >,
  TError = unknown,
>(
  params?: GetApiBudgetsListParams,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        QueryKey,
        GetApiBudgetsListParams['page']
      >
    >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
/**
 * @summary List budgets
 */

export function useGetApiBudgetsListInfinite<
  TData = InfiniteData<
    Awaited<ReturnType<typeof getApiBudgetsList>>,
    GetApiBudgetsListParams['page']
  >,
  TError = unknown,
>(
  params?: GetApiBudgetsListParams,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        TError,
        TData,
        Awaited<ReturnType<typeof getApiBudgetsList>>,
        QueryKey,
        GetApiBudgetsListParams['page']
      >
    >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> } {
  const queryOptions = getGetApiBudgetsListInfiniteQueryOptions(params, options)

  const query = useInfiniteQuery(queryOptions) as UseInfiniteQueryResult<TData, TError> & {
    queryKey: DataTag<QueryKey, TData>
  }

  query.queryKey = queryOptions.queryKey

  return query
}

export const getGetApiBudgetsListQueryOptions = <
  TData = Awaited<ReturnType<typeof getApiBudgetsList>>,
  TError = unknown,
>(
  params?: GetApiBudgetsListParams,
  options?: {
    query?: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsList>>, TError, TData>>
  },
) => {
  const { query: queryOptions } = options ?? {}

  const queryKey = queryOptions?.queryKey ?? getGetApiBudgetsListQueryKey(params)

  const queryFn: QueryFunction<Awaited<ReturnType<typeof getApiBudgetsList>>> = ({ signal }) =>
    getApiBudgetsList(params, signal)

  return { queryKey, queryFn, staleTime: 10000, ...queryOptions } as UseQueryOptions<
    Awaited<ReturnType<typeof getApiBudgetsList>>,
    TError,
    TData
  > & { queryKey: DataTag<QueryKey, TData> }
}

export type GetApiBudgetsListQueryResult = NonNullable<
  Awaited<ReturnType<typeof getApiBudgetsList>>
>
export type GetApiBudgetsListQueryError = unknown

export function useGetApiBudgetsList<
  TData = Awaited<ReturnType<typeof getApiBudgetsList>>,
  TError = unknown,
>(
  params: undefined | GetApiBudgetsListParams,
  options: {
    query: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsList>>, TError, TData>> &
      Pick<
        DefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiBudgetsList>>,
          TError,
          Awaited<ReturnType<typeof getApiBudgetsList>>
        >,
        'initialData'
      >
  },
): DefinedUseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiBudgetsList<
  TData = Awaited<ReturnType<typeof getApiBudgetsList>>,
  TError = unknown,
>(
  params?: GetApiBudgetsListParams,
  options?: {
    query?: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsList>>, TError, TData>> &
      Pick<
        UndefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiBudgetsList>>,
          TError,
          Awaited<ReturnType<typeof getApiBudgetsList>>
        >,
        'initialData'
      >
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiBudgetsList<
  TData = Awaited<ReturnType<typeof getApiBudgetsList>>,
  TError = unknown,
>(
  params?: GetApiBudgetsListParams,
  options?: {
    query?: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsList>>, TError, TData>>
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
/**
 * @summary List budgets
 */

export function useGetApiBudgetsList<
  TData = Awaited<ReturnType<typeof getApiBudgetsList>>,
  TError = unknown,
>(
  params?: GetApiBudgetsListParams,
  options?: {
    query?: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsList>>, TError, TData>>
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> } {
  const queryOptions = getGetApiBudgetsListQueryOptions(params, options)

  const query = useQuery(queryOptions) as UseQueryResult<TData, TError> & {
    queryKey: DataTag<QueryKey, TData>
  }

  query.queryKey = queryOptions.queryKey

  return query
}

/**
 * Create a new budget
 * @summary Create a budget
 */
export const postApiBudgetsCreate = (budgetPayload: BudgetPayload, signal?: AbortSignal) => {
  return customInstance<PostApiBudgetsCreate201>({
    url: `/api/budgets`,
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    data: budgetPayload,
    signal,
  })
}

export const getPostApiBudgetsCreateMutationOptions = <
  TError = PostApiBudgetsCreate400,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiBudgetsCreate>>,
    TError,
    { data: BudgetPayload },
    TContext
  >
}): UseMutationOptions<
  Awaited<ReturnType<typeof postApiBudgetsCreate>>,
  TError,
  { data: BudgetPayload },
  TContext
> => {
  const mutationKey = ['postApiBudgetsCreate']
  const { mutation: mutationOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey } }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof postApiBudgetsCreate>>,
    { data: BudgetPayload }
  > = (props) => {
    const { data } = props ?? {}

    return postApiBudgetsCreate(data)
  }

  return { mutationFn, ...mutationOptions }
}

export type PostApiBudgetsCreateMutationResult = NonNullable<
  Awaited<ReturnType<typeof postApiBudgetsCreate>>
>
export type PostApiBudgetsCreateMutationBody = BudgetPayload
export type PostApiBudgetsCreateMutationError = PostApiBudgetsCreate400

/**
 * @summary Create a budget
 */
export const usePostApiBudgetsCreate = <
  TError = PostApiBudgetsCreate400,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiBudgetsCreate>>,
    TError,
    { data: BudgetPayload },
    TContext
  >
}): UseMutationResult<
  Awaited<ReturnType<typeof postApiBudgetsCreate>>,
  TError,
  { data: BudgetPayload },
  TContext
> => {
  const mutationOptions = getPostApiBudgetsCreateMutationOptions(options)

  return useMutation(mutationOptions)
}
/**
 * Retrieve a budget by its ID
 * @summary Get a budget
 */
export const getApiBudgetsGet = (id: number, signal?: AbortSignal) => {
  return customInstance<GetApiBudgetsGet200>({ url: `/api/budgets/${id}`, method: 'GET', signal })
}

export const getGetApiBudgetsGetQueryKey = (id: number) => {
  return [`/api/budgets/${id}`] as const
}

export const getGetApiBudgetsGetInfiniteQueryOptions = <
  TData = InfiniteData<Awaited<ReturnType<typeof getApiBudgetsGet>>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>
    >
  },
) => {
  const { query: queryOptions } = options ?? {}

  const queryKey = queryOptions?.queryKey ?? getGetApiBudgetsGetQueryKey(id)

  const queryFn: QueryFunction<Awaited<ReturnType<typeof getApiBudgetsGet>>> = ({ signal }) =>
    getApiBudgetsGet(id, signal)

  return {
    queryKey,
    queryFn,
    enabled: !!id,
    staleTime: 10000,
    ...queryOptions,
  } as UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData> & {
    queryKey: DataTag<QueryKey, TData>
  }
}

export type GetApiBudgetsGetInfiniteQueryResult = NonNullable<
  Awaited<ReturnType<typeof getApiBudgetsGet>>
>
export type GetApiBudgetsGetInfiniteQueryError = GetApiBudgetsGet404

export function useGetApiBudgetsGetInfinite<
  TData = InfiniteData<Awaited<ReturnType<typeof getApiBudgetsGet>>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options: {
    query: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>
    > &
      Pick<
        DefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiBudgetsGet>>,
          TError,
          Awaited<ReturnType<typeof getApiBudgetsGet>>
        >,
        'initialData'
      >
  },
): DefinedUseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiBudgetsGetInfinite<
  TData = InfiniteData<Awaited<ReturnType<typeof getApiBudgetsGet>>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>
    > &
      Pick<
        UndefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiBudgetsGet>>,
          TError,
          Awaited<ReturnType<typeof getApiBudgetsGet>>
        >,
        'initialData'
      >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiBudgetsGetInfinite<
  TData = InfiniteData<Awaited<ReturnType<typeof getApiBudgetsGet>>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>
    >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
/**
 * @summary Get a budget
 */

export function useGetApiBudgetsGetInfinite<
  TData = InfiniteData<Awaited<ReturnType<typeof getApiBudgetsGet>>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options?: {
    query?: Partial<
      UseInfiniteQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>
    >
  },
): UseInfiniteQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> } {
  const queryOptions = getGetApiBudgetsGetInfiniteQueryOptions(id, options)

  const query = useInfiniteQuery(queryOptions) as UseInfiniteQueryResult<TData, TError> & {
    queryKey: DataTag<QueryKey, TData>
  }

  query.queryKey = queryOptions.queryKey

  return query
}

export const getGetApiBudgetsGetQueryOptions = <
  TData = Awaited<ReturnType<typeof getApiBudgetsGet>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options?: {
    query?: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>>
  },
) => {
  const { query: queryOptions } = options ?? {}

  const queryKey = queryOptions?.queryKey ?? getGetApiBudgetsGetQueryKey(id)

  const queryFn: QueryFunction<Awaited<ReturnType<typeof getApiBudgetsGet>>> = ({ signal }) =>
    getApiBudgetsGet(id, signal)

  return { queryKey, queryFn, enabled: !!id, staleTime: 10000, ...queryOptions } as UseQueryOptions<
    Awaited<ReturnType<typeof getApiBudgetsGet>>,
    TError,
    TData
  > & { queryKey: DataTag<QueryKey, TData> }
}

export type GetApiBudgetsGetQueryResult = NonNullable<Awaited<ReturnType<typeof getApiBudgetsGet>>>
export type GetApiBudgetsGetQueryError = GetApiBudgetsGet404

export function useGetApiBudgetsGet<
  TData = Awaited<ReturnType<typeof getApiBudgetsGet>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options: {
    query: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>> &
      Pick<
        DefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiBudgetsGet>>,
          TError,
          Awaited<ReturnType<typeof getApiBudgetsGet>>
        >,
        'initialData'
      >
  },
): DefinedUseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiBudgetsGet<
  TData = Awaited<ReturnType<typeof getApiBudgetsGet>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options?: {
    query?: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>> &
      Pick<
        UndefinedInitialDataOptions<
          Awaited<ReturnType<typeof getApiBudgetsGet>>,
          TError,
          Awaited<ReturnType<typeof getApiBudgetsGet>>
        >,
        'initialData'
      >
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
export function useGetApiBudgetsGet<
  TData = Awaited<ReturnType<typeof getApiBudgetsGet>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options?: {
    query?: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>>
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> }
/**
 * @summary Get a budget
 */

export function useGetApiBudgetsGet<
  TData = Awaited<ReturnType<typeof getApiBudgetsGet>>,
  TError = GetApiBudgetsGet404,
>(
  id: number,
  options?: {
    query?: Partial<UseQueryOptions<Awaited<ReturnType<typeof getApiBudgetsGet>>, TError, TData>>
  },
): UseQueryResult<TData, TError> & { queryKey: DataTag<QueryKey, TData> } {
  const queryOptions = getGetApiBudgetsGetQueryOptions(id, options)

  const query = useQuery(queryOptions) as UseQueryResult<TData, TError> & {
    queryKey: DataTag<QueryKey, TData>
  }

  query.queryKey = queryOptions.queryKey

  return query
}

/**
 * Update an existing budget
 * @summary Update a budget
 */
export const putApiBudgetsUpdate = (id: number, budgetPayload: BudgetPayload) => {
  return customInstance<PutApiBudgetsUpdate200>({
    url: `/api/budgets/${id}`,
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    data: budgetPayload,
  })
}

export const getPutApiBudgetsUpdateMutationOptions = <
  TError = PutApiBudgetsUpdate400 | PutApiBudgetsUpdate404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof putApiBudgetsUpdate>>,
    TError,
    { id: number; data: BudgetPayload },
    TContext
  >
}): UseMutationOptions<
  Awaited<ReturnType<typeof putApiBudgetsUpdate>>,
  TError,
  { id: number; data: BudgetPayload },
  TContext
> => {
  const mutationKey = ['putApiBudgetsUpdate']
  const { mutation: mutationOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey } }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof putApiBudgetsUpdate>>,
    { id: number; data: BudgetPayload }
  > = (props) => {
    const { id, data } = props ?? {}

    return putApiBudgetsUpdate(id, data)
  }

  return { mutationFn, ...mutationOptions }
}

export type PutApiBudgetsUpdateMutationResult = NonNullable<
  Awaited<ReturnType<typeof putApiBudgetsUpdate>>
>
export type PutApiBudgetsUpdateMutationBody = BudgetPayload
export type PutApiBudgetsUpdateMutationError = PutApiBudgetsUpdate400 | PutApiBudgetsUpdate404

/**
 * @summary Update a budget
 */
export const usePutApiBudgetsUpdate = <
  TError = PutApiBudgetsUpdate400 | PutApiBudgetsUpdate404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof putApiBudgetsUpdate>>,
    TError,
    { id: number; data: BudgetPayload },
    TContext
  >
}): UseMutationResult<
  Awaited<ReturnType<typeof putApiBudgetsUpdate>>,
  TError,
  { id: number; data: BudgetPayload },
  TContext
> => {
  const mutationOptions = getPutApiBudgetsUpdateMutationOptions(options)

  return useMutation(mutationOptions)
}
/**
 * Delete an existing budget
 * @summary Delete a budget
 */
export const deleteApiBudgetsDelete = (id: number) => {
  return customInstance<void>({ url: `/api/budgets/${id}`, method: 'DELETE' })
}

export const getDeleteApiBudgetsDeleteMutationOptions = <
  TError = DeleteApiBudgetsDelete404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof deleteApiBudgetsDelete>>,
    TError,
    { id: number },
    TContext
  >
}): UseMutationOptions<
  Awaited<ReturnType<typeof deleteApiBudgetsDelete>>,
  TError,
  { id: number },
  TContext
> => {
  const mutationKey = ['deleteApiBudgetsDelete']
  const { mutation: mutationOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey } }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof deleteApiBudgetsDelete>>,
    { id: number }
  > = (props) => {
    const { id } = props ?? {}

    return deleteApiBudgetsDelete(id)
  }

  return { mutationFn, ...mutationOptions }
}

export type DeleteApiBudgetsDeleteMutationResult = NonNullable<
  Awaited<ReturnType<typeof deleteApiBudgetsDelete>>
>

export type DeleteApiBudgetsDeleteMutationError = DeleteApiBudgetsDelete404

/**
 * @summary Delete a budget
 */
export const useDeleteApiBudgetsDelete = <
  TError = DeleteApiBudgetsDelete404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof deleteApiBudgetsDelete>>,
    TError,
    { id: number },
    TContext
  >
}): UseMutationResult<
  Awaited<ReturnType<typeof deleteApiBudgetsDelete>>,
  TError,
  { id: number },
  TContext
> => {
  const mutationOptions = getDeleteApiBudgetsDeleteMutationOptions(options)

  return useMutation(mutationOptions)
}
/**
 * Duplicate an existing budget
 * @summary Duplicate a budget
 */
export const postApiBudgetsDuplicate = (id: number, signal?: AbortSignal) => {
  return customInstance<PostApiBudgetsDuplicate201>({
    url: `/api/budgets/duplicate/${id}`,
    method: 'POST',
    signal,
  })
}

export const getPostApiBudgetsDuplicateMutationOptions = <
  TError = PostApiBudgetsDuplicate404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiBudgetsDuplicate>>,
    TError,
    { id: number },
    TContext
  >
}): UseMutationOptions<
  Awaited<ReturnType<typeof postApiBudgetsDuplicate>>,
  TError,
  { id: number },
  TContext
> => {
  const mutationKey = ['postApiBudgetsDuplicate']
  const { mutation: mutationOptions } = options
    ? options.mutation && 'mutationKey' in options.mutation && options.mutation.mutationKey
      ? options
      : { ...options, mutation: { ...options.mutation, mutationKey } }
    : { mutation: { mutationKey } }

  const mutationFn: MutationFunction<
    Awaited<ReturnType<typeof postApiBudgetsDuplicate>>,
    { id: number }
  > = (props) => {
    const { id } = props ?? {}

    return postApiBudgetsDuplicate(id)
  }

  return { mutationFn, ...mutationOptions }
}

export type PostApiBudgetsDuplicateMutationResult = NonNullable<
  Awaited<ReturnType<typeof postApiBudgetsDuplicate>>
>

export type PostApiBudgetsDuplicateMutationError = PostApiBudgetsDuplicate404

/**
 * @summary Duplicate a budget
 */
export const usePostApiBudgetsDuplicate = <
  TError = PostApiBudgetsDuplicate404,
  TContext = unknown,
>(options?: {
  mutation?: UseMutationOptions<
    Awaited<ReturnType<typeof postApiBudgetsDuplicate>>,
    TError,
    { id: number },
    TContext
  >
}): UseMutationResult<
  Awaited<ReturnType<typeof postApiBudgetsDuplicate>>,
  TError,
  { id: number },
  TContext
> => {
  const mutationOptions = getPostApiBudgetsDuplicateMutationOptions(options)

  return useMutation(mutationOptions)
}
