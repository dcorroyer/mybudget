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

  const maxValue = Math.max(...chartData.map((item) => item.balance)) * 1.1

  return (
    <div className='savings-chart-container' style={{ position: 'relative' }}>
      <div
        style={{
          position: 'absolute',
          top: '0',
          left: '0',
          right: '0',
          bottom: '20px',
          backgroundColor: 'var(--mantine-color-gray-0)',
          borderRadius: '8px',
          zIndex: 0,
        }}
      />

      {/* Le graphique avec un fond transparent */}
      <div style={{ position: 'relative', zIndex: 1 }}>
        <LineChart
          h={height || (isMobile ? 200 : 300)}
          data={chartData}
          dataKey='date'
          series={[{ name: 'balance', color: 'blue' }]}
          curveType='natural'
          withYAxis={true}
          yAxisProps={{
            domain: [0, maxValue],
            width: 0,
            tickFormatter: () => '',
          }}
          xAxisProps={{
            padding: { left: isMobile ? 5 : 30, right: isMobile ? 5 : 30 },
          }}
          gridAxis='none'
          withPointLabels
          withTooltip={false}
        />
      </div>
    </div>
  )
}
