import { Card, Container, Group, Modal, SimpleGrid, Text, Tooltip } from '@mantine/core'
import {
  IconChartLine,
  IconCreditCardPay,
  IconCreditCardRefund,
  IconEye,
} from '@tabler/icons-react'
import React, { useState } from 'react'
import { Chart } from 'react-google-charts'
import { generateSankeyData } from '../helpers/budgetDataTransformer'
import { BudgetFormDetails } from '../types/budgets'
import classes from './budget-table.module.css'

interface BudgetTableComponentProps {
  budgetValues?: BudgetFormDetails
}

export const BudgetTable: React.FC<BudgetTableComponentProps> = ({ budgetValues }) => {
  const incomes = budgetValues?.incomes ?? []
  const expenses = budgetValues?.expenses ?? []

  const [opened, setOpened] = useState(false)

  const graphData = generateSankeyData(expenses)
  const graphOptions = {}

  const openModal = () => setOpened(true)
  const closeModal = () => setOpened(false)

  return (
    <>
      <Container my={20}>
        <Card radius='lg'>
          <Text ta='center'>Budget Summary</Text>
        </Card>

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

        {/* TODO: After summary and before graph, add a section with pourcentage of incomes and expenses and savings capacity */}

        <Card radius='lg' py='xl' my='sm'>
          <Card.Section inheritPadding px='xl' pb='xs'>
            <Group justify='space-between' gap='xl' mt='xs'>
              <div className={classes.divIconBlue}>
                <IconChartLine className={classes.iconBlue} stroke={1.5} />
                <Text className={classes.resourceName} ml='xs'>
                  Graph
                </Text>
              </div>
              <Tooltip label='Open in full screen ?' position='top' withArrow>
                <div
                  onClick={openModal}
                  className={classes.divIconBlue}
                  style={{ cursor: 'pointer' }}
                >
                  <IconEye className={classes.iconBlue} stroke={1.5} />
                </div>
              </Tooltip>
            </Group>
          </Card.Section>
          <Card.Section inheritPadding mt='md' px='xl' pb='xs'>
            <Chart
              chartType='Sankey'
              width='100%'
              height='100%'
              data={graphData}
              options={graphOptions}
            />
          </Card.Section>
        </Card>

        <Modal
          opened={opened}
          onClose={closeModal}
          radius={12.5}
          size='100%'
          title='Expenses chart'
          centered
        >
          <Chart
            chartType='Sankey'
            width='100%'
            height='500px'
            data={graphData}
            options={graphOptions}
          />
        </Modal>
      </Container>
    </>
  )
}
