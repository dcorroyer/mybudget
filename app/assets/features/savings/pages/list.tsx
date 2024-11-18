import { CenteredLoader as Loader } from '@/components/centered-loader'
import { useAccount } from '@/features/accounts/hooks/useAccount'
import { Container, Group, MultiSelect, Select, Text } from '@mantine/core'
import React, { useState } from 'react'
import { SavingsChart } from '../components/savings-chart'
import { useSavings } from '../hooks/useSavings'

const SavingsList: React.FC = () => {
  const [selectedPeriod, setSelectedPeriod] = useState<string | null>(null)
  const [selectedAccounts, setSelectedAccounts] = useState<string[]>([])
  
  const { useAccountList } = useAccount()
  const { useBalanceHistory } = useSavings()
  
  const { data: accountList } = useAccountList()
  const { data: savingsData, isFetching } = useBalanceHistory({
    ...(selectedPeriod && { period: selectedPeriod as '3' | '6' | '12' }),
    ...(selectedAccounts.length > 0 && { accountIds: selectedAccounts.map(id => parseInt(id)) }),
  })

  const accountOptions = accountList?.data.map(account => ({
    value: account.id.toString(),
    label: account.name,
  })) || []

  const periodOptions = [
    { value: '3', label: 'Last 3 Months' },
    { value: '6', label: 'Last 6 Months' },
    { value: '12', label: 'Last Year' },
  ]

  if (isFetching) return <Loader />

  return (
    <>
      <Text fw={500} size="lg" pb="xl">
        Savings Evolution
      </Text>
      <Container>
        <Group align="flex-end" mb="xl">
          <MultiSelect
            label="Select Accounts"
            placeholder="All accounts"
            data={accountOptions}
            value={selectedAccounts}
            onChange={setSelectedAccounts}
            style={{ flex: 1 }}
          />
          <Select
            label="Period"
            placeholder="All time"
            clearable
            data={periodOptions}
            value={selectedPeriod}
            onChange={setSelectedPeriod}
          />
        </Group>
        
        {savingsData && <SavingsChart data={savingsData.data} />}
      </Container>
    </>
  )
}

export default SavingsList 