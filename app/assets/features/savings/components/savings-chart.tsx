import { Card, Group, Text } from '@mantine/core'
import { IconChartLine } from '@tabler/icons-react'
import React from 'react'
import { Chart } from 'react-google-charts'
import { SavingsResponse } from '../types/savings'
import classes from './savings-chart.module.css'

interface SavingsChartProps {
  data: SavingsResponse
}

export const SavingsChart: React.FC<SavingsChartProps> = ({ data }) => {
  const chartData = [
    ['Date', 'Balance'],
    ...data.balances.map((balance) => [balance.date, balance.balance]),
  ]

  const options = {
    curveType: 'function',
    legend: 'none',
  }

  return (
    <Card radius='lg' py='xl' mt='sm' shadow='sm'>
      <Card.Section inheritPadding px='xl' pb='xs'>
        <Group justify='space-between' gap='xl' mt='xs'>
          <div className={classes.divIconBlue}>
            <IconChartLine className={classes.iconBlue} stroke={1.5} />
            <Text className={classes.resourceName} ml='xs'>
              Savings Evolution
            </Text>
          </div>
        </Group>
      </Card.Section>
      <Card.Section inheritPadding px='xl' pb='xs'>
        <Chart
          chartType='LineChart'
          width='100%'
          height='400px'
          data={chartData}
          options={options}
        />
      </Card.Section>
    </Card>
  )
}
