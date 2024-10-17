import React from 'react'

import { Card, Group, SimpleGrid, Text } from '@mantine/core'
import { IconCreditCardPay, IconCreditCardRefund } from '@tabler/icons-react'

import { BudgetFormDetails } from '../types/budgets'

import classes from './budget-table.module.css'

interface BudgetTableComponentProps {
  budgetValues?: BudgetFormDetails
}

export const BudgetTable: React.FC<BudgetTableComponentProps> = ({ budgetValues }) => {
  const incomes = budgetValues?.incomes ?? []
  const expenses = budgetValues?.expenses ?? []

  return (
    <>
      <Card radius='lg' py='xl' mt='sm'>
        <Card.Section inheritPadding px='xl' pb='xs'>
          <Group justify='left' gap='xl' mt='xs'>
            <div className={classes.divIconGreen}>
              <IconCreditCardRefund className={classes.iconGreen} stroke={1.5} />
              <Text className={classes.resourceName} ml='xs'>
                Incomes
              </Text>
            </div>
          </Group>
        </Card.Section>
        <Card.Section inheritPadding mt='md' px='xl' pb='xs'>
          {incomes.map((income, incomeIndex) => (
            <div key={incomeIndex}>
              <Group justify='space-between' className={classes.categoryBlock} c='gray' mb='sm'>
                <Text className={classes.categoryName} mx='xs' fw='bold'>
                  {income.name}
                </Text>
                <Text mx='xs' fw='bold'>
                  {income.amount} €
                </Text>
              </Group>
            </div>
          ))}
        </Card.Section>
      </Card>

      <Card radius='lg' py='xl' mt='sm'>
        <Card.Section inheritPadding px='xl' pb='xs'>
          <Group justify='left' gap='xl' mt='xs'>
            <div className={classes.divIconRed}>
              <IconCreditCardPay className={classes.iconRed} stroke={1.5} />
              <Text className={classes.resourceName} ml='xs'>
                Expenses
              </Text>
            </div>
          </Group>
        </Card.Section>
        <Card.Section inheritPadding mt='sm' px='xl' pb='xs'>
          {expenses.map((expenseCategory, categoryIndex) => {
            const totalAmount = expenseCategory.items.reduce((sum, item) => sum + item.amount, 0)

            return (
              <div key={categoryIndex}>
                <Group justify='space-between' className={classes.categoryBlock} mb='sm'>
                  <Text className={classes.categoryName} mx='xs' c='gray' fw='bold'>
                    {expenseCategory.category}
                  </Text>
                  <Text m='xs' c='gray' fw='bold'>
                    {totalAmount} €
                  </Text>
                </Group>
                {expenseCategory.items.map((expenseItem, itemIndex) => (
                  <SimpleGrid mb='sm' key={itemIndex}>
                    <Group justify='space-between' mx='xs'>
                      <Text>{expenseItem.name}</Text>
                      <Text>{expenseItem.amount} €</Text>
                    </Group>
                  </SimpleGrid>
                ))}
              </div>
            )
          })}
        </Card.Section>
      </Card>
    </>
  )
}
