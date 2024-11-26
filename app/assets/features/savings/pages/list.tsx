import { useAccount } from '@/features/accounts/hooks/useAccount'
import { Button, Container, Group, Loader, MultiSelect, Text } from '@mantine/core'
import React, { useState } from 'react'
import { SavingsChart } from '../components/savings-chart'
import { useSavings } from '../hooks/useSavings'

const SavingsList: React.FC = () => {
  const [selectedPeriod, setSelectedPeriod] = useState<string>('6')
  const [selectedAccounts, setSelectedAccounts] = useState<string[]>([])

  const { useAccountList } = useAccount()
  const { useBalanceHistory } = useSavings()
  const { data: accountList } = useAccountList()

  const { data: savingsData, isFetching } = useBalanceHistory({
    ...(selectedPeriod && { period: selectedPeriod as '3' | '6' | '12' }),
    ...(selectedAccounts.length > 0 && { accountIds: selectedAccounts.map((id) => parseInt(id)) }),
  })

  const accountOptions =
    accountList?.data.map((account) => ({
      value: account.id.toString(),
      label: account.name,
    })) || []

  if (isFetching) return <Loader />

  return (
    <>
      <Text fw={500} size='lg' pb='xl' mt='md'>
        Savings
      </Text>
      <Container>
        <Group align='flex-end' mb='xl'>
          <MultiSelect
            label='Select Accounts'
            placeholder={selectedAccounts.length === 0 ? 'All accounts' : ''}
            data={accountOptions}
            value={selectedAccounts}
            onChange={setSelectedAccounts}
            searchable
            clearable
            style={{ flex: 1 }}
          />
          <Group>
            <Button
              variant={selectedPeriod === '12' ? 'filled' : 'light'}
              onClick={() => setSelectedPeriod('12')}
            >
              12 Months
            </Button>
            <Button
              variant={selectedPeriod === '6' ? 'filled' : 'light'}
              onClick={() => setSelectedPeriod('6')}
            >
              6 Months
            </Button>
            <Button
              variant={selectedPeriod === '3' ? 'filled' : 'light'}
              onClick={() => setSelectedPeriod('3')}
            >
              3 Months
            </Button>
            <Button
              variant={selectedPeriod === '' ? 'filled' : 'light'}
              onClick={() => setSelectedPeriod('')}
            >
              All Time
            </Button>
          </Group>
        </Group>

        {savingsData && <SavingsChart data={savingsData.data} />}
      </Container>
    </>
  )
}

export default SavingsList
