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
import { useAccount } from '../hooks/useAccount'
import { useTransactions } from '../hooks/useTransactions'
import { createTransactionFormType, transactionFormSchema } from '../schemas/transactions'
import { Transaction } from '../types/transactions'

interface TransactionFormComponentProps {
  initialValues?: Transaction
  isLoading?: boolean
  onSuccess?: () => void
  onClose?: () => void
}

export const TransactionForm: React.FC<TransactionFormComponentProps> = ({
  initialValues,
  onSuccess,
}) => {
  const { useAccountList } = useAccount()
  const { data: accountList, isFetching } = useAccountList()
  const { createTransaction, updateTransaction, isLoading } = useTransactions()

  const form = useForm<createTransactionFormType>({
    initialValues: initialValues || {
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
      createTransaction({ accountId: values.account.id, values })
    } else if (initialValues && initialValues.id) {
      updateTransaction({
        accountId: values.account.id,
        transactionId: initialValues.id,
        values: values,
      })
    }
    handleSuccess()
  }

  const handleSuccess = () => {
    onSuccess?.()
  }

  return (
    <form onSubmit={form.onSubmit(onSubmit)}>
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
                      { value: 'CREDIT', label: 'Crédit (+)' },
                      { value: 'DEBIT', label: 'Débit (-)' },
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
                      accountList?.data.map((account) => ({
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
