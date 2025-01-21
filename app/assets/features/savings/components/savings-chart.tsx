import { LineChart } from '@mantine/charts'
import { Badge, Card, em, Group, Text } from '@mantine/core'
import { useMediaQuery } from '@mantine/hooks'
import { IconChartLine } from '@tabler/icons-react'
import React from 'react'
import { SavingsResponse } from '../types/savings'

interface SavingsChartProps {
  data: SavingsResponse
}

export const SavingsChart: React.FC<SavingsChartProps> = ({ data }) => {
  const isMobile = useMediaQuery(`(max-width: ${em(750)})`)

  const chartData = data.balances.map((balance) => ({
    date: balance.date,
    balance: balance.balance,
  }))

  return (
    <Card radius='lg' py='xl' mt='sm' shadow='sm'>
      <Card.Section inheritPadding px='xl' pb='xs'>
        <Group justify='space-between' my='md'>
          <Group gap='xs'>
            <IconChartLine size={20} style={{ color: 'var(--mantine-color-blue-6)' }} />
            <Text fw={500} size='md'>
              Évolution de l'épargne
            </Text>
          </Group>
          <Badge size='lg' variant='light'>
            Total: {chartData[chartData.length - 1]?.balance.toLocaleString()} €
          </Badge>
        </Group>
      </Card.Section>
      <Card.Section inheritPadding px='xl' mt='sm'>
        <LineChart
          h={isMobile ? 200 : 300}
          data={chartData}
          dataKey='date'
          series={[{ name: 'balance', color: 'blue' }]}
          curveType='natural'
          withYAxis={false}
          xAxisProps={{ padding: { left: isMobile ? 5 : 30, right: isMobile ? 5 : 30 } }}
          withPointLabels
          withTooltip={false}
        />
      </Card.Section>
    </Card>
  )
}
