import { Card, Skeleton } from '@mantine/core'
import React from 'react'
import { useSavings } from '../hooks/useSavings'
import { SavingsChart } from './savings-chart'

interface SavingsChartSectionProps {
  selectedPeriod: string
  selectedAccounts: string[]
}

export const SavingsChartSection = ({
  selectedPeriod,
  selectedAccounts,
}: SavingsChartSectionProps) => {
  const { useBalanceHistory } = useSavings()
  const { data: savingsData, isFetching } = useBalanceHistory({
    ...(selectedPeriod && { period: selectedPeriod as '3' | '6' | '12' }),
    ...(selectedAccounts.length > 0 && {
      accountIds: selectedAccounts.map((id) => parseInt(id)),
    }),
  })

  if (isFetching) {
    return (
      <Card radius='lg' py='xl' mt='sm' shadow='sm'>
        <Card.Section inheritPadding px='xl' mt='sm'>
          <Skeleton height={300} radius='md' animate={true} />
        </Card.Section>
      </Card>
    )
  }

  return savingsData ? <SavingsChart data={savingsData.data} /> : null
}
