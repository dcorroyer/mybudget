import {
  usePatchApiAccountsUpdate,
  usePostApiAccountsCreate,
} from '@/api/generated/accounts/accounts'
import { useMutationWithInvalidation } from '@/hooks/useMutation'
import { Button, Card, Group, Stack, Text, TextInput, rem } from '@mantine/core'
import { useForm } from '@mantine/form'
import { notifications } from '@mantine/notifications'
import { IconBuildingBank, IconCheck } from '@tabler/icons-react'
import { zodResolver } from 'mantine-form-zod-resolver'
import React from 'react'
import { accountFormSchema, createAccountFormType } from '../schemas/accountSchema'
import { formatAccountZodErrors } from '../utils/validationUtils'

interface AccountFormComponentProps {
  initialValues?: {
    id?: number
    name: string
  }
  onSuccess?: () => void
}

export const AccountForm: React.FC<AccountFormComponentProps> = ({ initialValues, onSuccess }) => {
  const form = useForm<createAccountFormType>({
    initialValues: initialValues || {
      name: '',
    },
    validate: zodResolver(accountFormSchema),
  })

  const { mutate: createAccount, isPending: isCreating } = useMutationWithInvalidation(
    usePostApiAccountsCreate().mutateAsync,
    {
      queryKeyToInvalidate: ['/api/accounts', '/api/accounts/balance-history'],
      successMessage: 'Compte créé avec succès',
      errorMessage: 'Une erreur est survenue lors de la création du compte',
      onSuccess,
    },
  )

  const { mutate: updateAccount, isPending: isUpdating } = useMutationWithInvalidation(
    usePatchApiAccountsUpdate().mutateAsync,
    {
      queryKeyToInvalidate: ['/api/accounts', '/api/accounts/balance-history'],
      successMessage: 'Compte mis à jour avec succès',
      errorMessage: 'Une erreur est survenue lors de la mise à jour du compte',
      onSuccess,
    },
  )

  const isLoading = isCreating || isUpdating
  const isEditMode = !!initialValues?.id

  const onSubmit = (values: createAccountFormType) => {
    const validationResult = accountFormSchema.safeParse(values)

    if (!validationResult.success) {
      const formattedErrors = formatAccountZodErrors(validationResult.error)

      const ErrorMessage = () => (
        <div style={{ whiteSpace: 'pre-wrap', maxHeight: '60vh', overflow: 'auto' }}>
          <div style={{ marginBottom: '8px' }}>Veuillez corriger les erreurs suivantes :</div>
          {formattedErrors.map((error, index) => (
            <div key={index} style={{ marginBottom: '8px' }}>
              {error}
            </div>
          ))}
        </div>
      )

      notifications.show({
        title: 'Erreur de validation',
        message: <ErrorMessage />,
        color: 'red',
        autoClose: false,
      })
      return
    }

    if (!isEditMode) {
      createAccount({ data: values })
    } else if (initialValues?.id) {
      updateAccount({
        id: initialValues.id,
        data: values,
      })
    }
  }

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault()
        onSubmit(form.values)
      }}
    >
      <Stack gap='md'>
        <Card radius='lg' shadow='sm'>
          <Card.Section inheritPadding px='xl' pb='xs'>
            <Group gap='xs' my='md'>
              <IconBuildingBank
                style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-blue-6)' }}
              />
              <Text fw={500} size='md'>
                Détails du compte
              </Text>
            </Group>
          </Card.Section>

          <Card.Section withBorder inheritPadding px='xl' py='md'>
            <Stack gap='md'>
              <TextInput
                label='Nom du compte'
                placeholder='ex: Livret A, PEL...'
                {...form.getInputProps('name')}
                leftSection={<IconBuildingBank style={{ width: rem(16), height: rem(16) }} />}
                styles={{
                  input: {
                    backgroundColor: 'var(--mantine-color-gray-0)',
                  },
                }}
              />
            </Stack>
          </Card.Section>

          <Card.Section inheritPadding px='xl' py='md'>
            <Group justify='flex-end' gap='sm'>
              <Button variant='light' color='gray' onClick={onSuccess}>
                Annuler
              </Button>
              <Button
                type='submit'
                loading={isLoading}
                leftSection={<IconCheck style={{ width: rem(16), height: rem(16) }} />}
              >
                {isEditMode ? 'Mettre à jour' : 'Créer'}
              </Button>
            </Group>
          </Card.Section>
        </Card>
      </Stack>
    </form>
  )
}
