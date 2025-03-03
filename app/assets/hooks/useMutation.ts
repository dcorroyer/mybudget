import { notifications } from '@mantine/notifications'
import {
  UseMutationOptions,
  UseMutationResult,
  useMutation,
  useQueryClient,
} from '@tanstack/react-query'

interface UseMutationWithInvalidationOptions<TData, TError, TVariables>
  extends Omit<UseMutationOptions<TData, TError, TVariables>, 'onSuccess' | 'onError'> {
  onSuccess?: (data: TData, variables: TVariables) => void
  onError?: (error: TError, variables: TVariables) => void
  successMessage?: string
  errorMessage?: string
  queryKeyToInvalidate: string[]
}

export function useMutationWithInvalidation<TData, TError, TVariables>(
  mutationFn: (variables: TVariables) => Promise<TData>,
  options: UseMutationWithInvalidationOptions<TData, TError, TVariables>,
): UseMutationResult<TData, TError, TVariables> {
  const queryClient = useQueryClient()

  return useMutation({
    ...options,
    mutationFn,
    onSuccess: (data, variables) => {
      // Invalider les queries spécifiées
      options.queryKeyToInvalidate.forEach((queryKey) => {
        queryClient.invalidateQueries({ queryKey: [queryKey] })
      })

      // Afficher la notification de succès si un message est fourni
      if (options.successMessage) {
        notifications.show({
          title: 'Succès',
          message: options.successMessage,
          color: 'green',
        })
      }

      // Appeler le callback onSuccess personnalisé
      options.onSuccess?.(data, variables)
    },
    onError: (error, variables) => {
      // Afficher la notification d'erreur si un message est fourni
      if (options.errorMessage) {
        notifications.show({
          title: 'Erreur',
          message: options.errorMessage,
          color: 'red',
        })
      }

      // Appeler le callback onError personnalisé
      options.onError?.(error, variables)
    },
  })
}
