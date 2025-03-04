import { BalanceHistoryResponse } from '@/api/models'
import { LineChart } from '@mantine/charts'
import { em } from '@mantine/core'
import { useMediaQuery } from '@mantine/hooks'
import React from 'react'

interface SavingsChartProps {
  data: BalanceHistoryResponse
  height?: number
}

export const SavingsChart: React.FC<SavingsChartProps> = ({ data, height }) => {
  const isMobile = useMediaQuery(`(max-width: ${em(750)})`)

  const chartData = data.balances.map((balance) => ({
    date: balance.date,
    balance: balance.balance,
  }))

  return (
    <>
      <LineChart
        h={height || (isMobile ? 200 : 300)}
        data={chartData}
        dataKey='date'
        series={[{ name: 'balance', color: 'blue' }]}
        curveType='natural'
        withYAxis={false}
        xAxisProps={{ padding: { left: isMobile ? 5 : 30, right: isMobile ? 5 : 30 } }}
        withPointLabels
        withTooltip={false}
      />
    </>
  )
}
