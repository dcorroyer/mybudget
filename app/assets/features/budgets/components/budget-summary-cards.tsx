import { Card, Group, Text } from '@mantine/core'
import { Icon } from '@tabler/icons-react'
import React from 'react'
import classes from './budget-summary-cards.module.css'

interface BudgetSummaryCardProps {
  icon: Icon
  color: 'blue' | 'green' | 'red'
  title: string
  amount: number
}

export const BudgetSummaryCards = ({
  icon: IconComponent,
  color,
  title,
  amount,
}: BudgetSummaryCardProps) => {
  return (
    <Card radius='lg' pb='xl' shadow='sm'>
      <Card.Section inheritPadding py='xs'>
        <Group justify='left' gap='xl' mt='xs'>
          <div className={classes[`divIcon${color.charAt(0).toUpperCase()}${color.slice(1)}`]}>
            <IconComponent
              className={classes[`icon${color.charAt(0).toUpperCase()}${color.slice(1)}`]}
              stroke={1.5}
            />
          </div>
        </Group>
      </Card.Section>
      <Card.Section inheritPadding py='xs'>
        <Group justify='space-between'>
          <Text fw={500}>{title}</Text>
        </Group>
        <Text fw={500} c={color}>
          {amount} €
        </Text>
      </Card.Section>
    </Card>
  )
}
