import { useGetApiAccountsList } from '@/api/generated/accounts/accounts'
import {
  usePostApiTransactionsCreate,
  usePutApiTransactionUpdate,
} from '@/api/generated/transactions/transactions'
import {
  AccountResponse,
  TransactionPayloadType,
  TransactionResponse,
  TransactionResponseType,
} from '@/api/models'
import { useMutationWithInvalidation } from '@/hooks/useMutation'
import {
  Button,
  Card,
  Grid,
  Group,
  NumberInput,
  rem,
  Select,
  Stack,
  Text,
  TextInput,
} from '@mantine/core'
import { DatePickerInput } from '@mantine/dates'
import { useForm } from '@mantine/form'
import {
  IconArrowsExchange,
  IconBuildingBank,
  IconCalendar,
  IconCheck,
  IconCurrencyEuro,
  IconReceipt2,
} from '@tabler/icons-react'
import { zodResolver } from 'mantine-form-zod-resolver'
import React, { useEffect, useState } from 'react'
import { createTransactionFormType, transactionFormSchema } from '../schemas/transactionSchema'

interface TransactionFormComponentProps {
  initialValues?: TransactionResponse
  isLoading?: boolean
  onSuccess?: () => void
  onClose?: () => void
}

export const TransactionForm: React.FC<TransactionFormComponentProps> = ({
  initialValues,
  onSuccess,
}) => {
  const { data: accountList, isFetching } = useGetApiAccountsList()
  const { mutate: createTransaction, isPending: isCreating } = useMutationWithInvalidation(
    usePostApiTransactionsCreate().mutateAsync,
    {
      queryKeyToInvalidate: [
        '/api/accounts',
        '/api/accounts/transactions',
        '/api/accounts/balance-history',
      ],
      successMessage: 'Transaction créée avec succès',
      errorMessage: 'Une erreur est survenue lors de la création de la transaction',
      onSuccess,
    },
  )
  const { mutate: updateTransaction, isPending: isUpdating } = useMutationWithInvalidation(
    usePutApiTransactionUpdate().mutateAsync,
    {
      queryKeyToInvalidate: [
        '/api/accounts',
        '/api/accounts/transactions',
        '/api/accounts/balance-history',
      ],
      successMessage: 'Transaction mise à jour avec succès',
      errorMessage: 'Une erreur est survenue lors de la mise à jour de la transaction',
      onSuccess,
    },
  )
  const isLoading = isCreating || isUpdating

  const form = useForm<createTransactionFormType>({
    initialValues: initialValues
      ? {
          description: initialValues.description,
          amount: initialValues.amount,
          type: initialValues.type === TransactionResponseType.CREDIT ? 'CREDIT' : 'DEBIT',
          date: new Date(initialValues.date),
          account: {
            id: initialValues.account.id,
            name: initialValues.account.name,
          },
        }
      : {
          description: '',
          amount: 0,
          type: 'CREDIT',
          date: new Date(),
          account: {
            id: 0,
            name: '',
          },
        },
    validate: zodResolver(transactionFormSchema),
  })

  const [dateValue, setDateValue] = useState<Date>(new Date())
  const [accountIdValue, setAccountIdValue] = useState<number>(0)
  const [isEditMode, setIsEditMode] = useState<boolean>(false)

  useEffect(() => {
    if (initialValues) {
      const initialDate = initialValues.date ? new Date(initialValues.date) : null
      if (initialDate) {
        setDateValue(initialDate)
        form.setValues({ date: initialDate })
      }
      setIsEditMode(true)
    } else {
      setIsEditMode(false)
    }
  }, [initialValues, form.setValues])

  const onSubmit = (values: createTransactionFormType) => {
    if (!isEditMode) {
      createTransaction({ accountId: values.account.id, data: values })
    } else if (initialValues && initialValues.id) {
      updateTransaction({
        accountId: values.account.id,
        id: initialValues.id,
        data: values,
      })
    }
  }

  return (
    <form
      onSubmit={(e) => {
        e.preventDefault()
        const values = form.values
        onSubmit(values)
      }}
    >
      <Stack gap='md'>
        <Card radius='lg' shadow='sm'>
          <Card.Section inheritPadding px='xl' pb='xs'>
            <Group gap='xs' my='md'>
              <IconReceipt2
                style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-blue-6)' }}
              />
              <Text fw={500} size='md'>
                Détails de la transaction
              </Text>
            </Group>
          </Card.Section>

          <Card.Section withBorder inheritPadding px='xl' py='md'>
            <Stack gap='md'>
              <Grid gutter='md'>
                <Grid.Col span={6}>
                  <DatePickerInput
                    label='Date'
                    placeholder='Sélectionnez une date'
                    {...form.getInputProps('date')}
                    value={dateValue}
                    onChange={(date) => {
                      form.setFieldValue('date', date!)
                      setDateValue(date!)
                    }}
                    leftSection={<IconCalendar style={{ width: rem(16), height: rem(16) }} />}
                    styles={{
                      input: {
                        backgroundColor: 'var(--mantine-color-gray-0)',
                      },
                    }}
                  />
                </Grid.Col>
                <Grid.Col span={6}>
                  <Select
                    label='Type'
                    data={[
                      { value: TransactionPayloadType.CREDIT, label: 'Crédit (+)' },
                      { value: TransactionPayloadType.DEBIT, label: 'Débit (-)' },
                    ]}
                    {...form.getInputProps('type')}
                    leftSection={<IconArrowsExchange style={{ width: rem(16), height: rem(16) }} />}
                    styles={{
                      input: {
                        backgroundColor: 'var(--mantine-color-gray-0)',
                      },
                    }}
                  />
                </Grid.Col>
              </Grid>

              <TextInput
                label='Description'
                placeholder='ex: Épargne mensuelle, Achat...'
                {...form.getInputProps('description')}
                styles={{
                  input: {
                    backgroundColor: 'var(--mantine-color-gray-0)',
                  },
                }}
              />

              <Grid gutter='md'>
                <Grid.Col span={6}>
                  <NumberInput
                    label='Montant'
                    placeholder='0'
                    min={0}
                    {...form.getInputProps('amount')}
                    rightSection={<IconCurrencyEuro style={{ width: rem(16), height: rem(16) }} />}
                    styles={{
                      input: {
                        backgroundColor: 'var(--mantine-color-gray-0)',
                      },
                    }}
                  />
                </Grid.Col>
                <Grid.Col span={6}>
                  <Select
                    {...form.getInputProps('account.id')}
                    label='Compte'
                    placeholder={
                      initialValues?.account.id
                        ? `${initialValues?.account.name}`
                        : 'Sélectionnez un compte'
                    }
                    data={
                      accountList?.data?.map((account: AccountResponse) => ({
                        value: account.id.toString(),
                        label: account.name,
                      })) || []
                    }
                    value={accountIdValue?.toString()}
                    onChange={(accountId) => {
                      if (accountId) {
                        form.setFieldValue('account.id', parseInt(accountId))
                        setAccountIdValue(parseInt(accountId))
                      }
                    }}
                    disabled={isFetching || initialValues?.account?.id ? true : false}
                    leftSection={<IconBuildingBank style={{ width: rem(16), height: rem(16) }} />}
                    styles={{
                      input: {
                        backgroundColor: 'var(--mantine-color-gray-0)',
                      },
                    }}
                  />
                </Grid.Col>
              </Grid>
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
