import { useGetApiBalanceHistory } from '@/api/generated/balance-history/balance-history'
import { GetApiBalanceHistoryParams, PeriodsEnum } from '@/api/models'
import { Badge, Card, Group, Skeleton, Text, rem } from '@mantine/core'
import { IconChartLine } from '@tabler/icons-react'
import React, { useEffect, useState } from 'react'
import { SavingsChart } from './SavingsChart'

interface SavingsChartSectionProps {
  selectedPeriod: string
  selectedAccounts: string[]
}

export const SavingsChartSection = ({
  selectedPeriod,
  selectedAccounts,
}: SavingsChartSectionProps) => {
  const [prevData, setPrevData] = useState<any>(null)

  const queryParams: GetApiBalanceHistoryParams = {
    ...(selectedPeriod && { period: selectedPeriod as PeriodsEnum }),
    ...(selectedAccounts.length && { 'accountIds[]': selectedAccounts.map(Number) }),
  }

  const { data: savingsData, isFetching } = useGetApiBalanceHistory(queryParams)

  useEffect(() => {
    if (!isFetching && savingsData) {
      setPrevData(savingsData)
    }
  }, [savingsData, isFetching])

  const showSkeleton = isFetching && !prevData
  const showPrevData = isFetching && prevData && prevData.data
  const showCurrentData = !isFetching && savingsData && savingsData.data

  const currentTotal = savingsData?.data?.balances.length
    ? savingsData.data.balances[savingsData.data.balances.length - 1].balance
    : 0

  return (
    <Card radius='lg' shadow='sm'>
      <Card.Section inheritPadding px='xl' pb='xs'>
        <Group justify='space-between' my='md'>
          <Group gap='xs'>
            <IconChartLine
              style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-blue-6)' }}
            />
            <Text fw={500} size='md'>
              Évolution de l&apos;épargne
            </Text>
          </Group>
          {(showCurrentData || showPrevData) && (
            <Badge size='lg' variant='light'>
              Total: {currentTotal.toLocaleString()} €
            </Badge>
          )}
        </Group>
      </Card.Section>

      <Card.Section withBorder inheritPadding px='xl' py='md'>
        <div style={{ position: 'relative', height: '350px', width: '100%', overflow: 'visible' }}>
          {showSkeleton && <Skeleton height={350} radius='md' animate={true} />}

          {showPrevData && prevData && prevData.data && (
            <div style={{ position: 'absolute', top: 0, left: 0, right: 0 }}>
              <SavingsChart data={prevData.data} height={350} />
            </div>
          )}

          {showCurrentData && savingsData && savingsData.data && (
            <div style={{ position: 'absolute', top: 0, left: 0, right: 0 }}>
              <SavingsChart data={savingsData.data} height={350} />
            </div>
          )}
        </div>
      </Card.Section>
    </Card>
  )
}
