import { useGetApiBalanceHistory } from '@/api/generated/balance-history/balance-history'
import { Card, Skeleton } from '@mantine/core'
import React from 'react'
import { SavingsChart } from './SavingsChart'

interface SavingsChartSectionProps {
  selectedPeriod: string
  selectedAccounts: string[]
}

export const SavingsChartSection = ({
  selectedPeriod,
  selectedAccounts,
}: SavingsChartSectionProps) => {
  const { data: savingsData, isFetching } = useGetApiBalanceHistory({
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

  return savingsData?.data ? <SavingsChart data={savingsData.data} /> : null
}
