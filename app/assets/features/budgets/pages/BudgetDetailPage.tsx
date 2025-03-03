import { useGetApiBudgetsGet } from '@/api/generated/budgets/budgets'
import { IncomeResponse } from '@/api/models'
import { CenteredLoader } from '@/components/CenteredLoader'
import NotFound from '@/components/NotFound'
import {
  ActionIcon,
  Badge,
  Card,
  Container,
  Grid,
  Group,
  Modal,
  rem,
  Stack,
  Text,
  Title,
  Tooltip,
} from '@mantine/core'
import { useMediaQuery } from '@mantine/hooks'
import {
  IconChartLine,
  IconChevronLeft,
  IconEye,
  IconPencil,
  IconPencilOff,
  IconPigMoney,
  IconReceipt2,
  IconWallet,
} from '@tabler/icons-react'
import React, { useState } from 'react'
import { Chart } from 'react-google-charts'
import { Link, useParams } from 'react-router-dom'
import { BudgetForm } from '../components/BudgetForm'
import { useGroupedExpenses } from '../hooks/useBudgets'
import {
  calculateCategoryTotal,
  calculatePercentage,
  formatAmount,
  generateSankeyData,
  parseDateFromYYYYMM,
} from '../utils/budgetUtils'

const DottedLine = () => (
  <div
    style={{
      flex: 1,
      borderBottom: '2px dotted var(--mantine-color-gray-3)',
      margin: '0 0.5rem',
      height: '1px',
    }}
  />
)

const BudgetDetail = () => {
  const [editMode, setEditMode] = useState(false)
  const [chartModalOpened, setChartModalOpened] = useState(false)
  const { id } = useParams()
  const isMobile = useMediaQuery('(max-width: 750px)')

  const { data: budget, isFetching } = useGetApiBudgetsGet(Number(id))
  const formattedExpenses = useGroupedExpenses(budget?.data?.expenses || [])

  if (isFetching) return <CenteredLoader />
  if (!budget?.data) return <NotFound />

  const budgetData = {
    id: budget.data.id,
    name: budget.data.name,
    date: budget.data.date,
    incomes: budget.data.incomes || [],
    expenses: budget.data.expenses || [],
    savingCapacity: budget.data.savingCapacity || 0,
    incomesAmount: budget.data.incomesAmount || 0,
    expensesAmount: budget.data.expensesAmount || 0,
  }

  const toggleEditMode = () => setEditMode(!editMode)
  const openChartModal = () => setChartModalOpened(true)
  const closeChartModal = () => setChartModalOpened(false)

  const graphData = generateSankeyData(formattedExpenses)
  const graphOptions = {}

  if (editMode) {
    return <BudgetForm initialValues={budgetData} onClose={() => setEditMode(false)} />
  }

  const formatDate = (dateStr: string) => {
    const date = parseDateFromYYYYMM(dateStr)
    return new Intl.DateTimeFormat('fr-FR', { month: 'long', year: 'numeric' }).format(date)
  }

  return (
    <Container size='xl' py='xl'>
      <Stack gap='md'>
        {/* Header */}
        <Group justify='space-between' align='flex-end' mb='lg'>
          <Group align='center' gap='xs'>
            <ActionIcon variant='light' color='blue' component={Link} to='/budgets' size='lg'>
              <IconChevronLeft style={{ width: rem(20), height: rem(20) }} />
            </ActionIcon>
            <Stack gap={0}>
              <Title order={1} size='h2' fw={600} c='blue.7'>
                Budget de {formatDate(budget.data.date)}
              </Title>
              <Text c='dimmed' size='sm'>
                Détail du budget mensuel
              </Text>
            </Stack>
          </Group>
          <ActionIcon variant='light' color='blue' onClick={toggleEditMode} size='lg'>
            {editMode ? (
              <IconPencilOff style={{ width: rem(20), height: rem(20) }} />
            ) : (
              <IconPencil style={{ width: rem(20), height: rem(20) }} />
            )}
          </ActionIcon>
        </Group>

        {/* Summary Cards */}
        <Grid gutter='lg'>
          <Grid.Col span={{ base: 12, md: 4 }}>
            <Card radius='lg' shadow='sm' p='lg'>
              <Group gap='xs' mb='md'>
                <IconPigMoney
                  style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-blue-6)' }}
                />
                <Text fw={500}>Capacité d&apos;épargne</Text>
              </Group>
              <Text size='xl' fw={700} c='blue'>
                {formatAmount(budgetData.savingCapacity)} €
              </Text>
            </Card>
          </Grid.Col>
          <Grid.Col span={{ base: 12, md: 4 }}>
            <Card radius='lg' shadow='sm' p='lg'>
              <Group gap='xs' mb='md'>
                <IconWallet
                  style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-teal-6)' }}
                />
                <Text fw={500}>Revenus</Text>
              </Group>
              <Text size='xl' fw={700} c='teal'>
                {formatAmount(budgetData.incomesAmount)} €
              </Text>
            </Card>
          </Grid.Col>
          <Grid.Col span={{ base: 12, md: 4 }}>
            <Card radius='lg' shadow='sm' p='lg'>
              <Group gap='xs' mb='md'>
                <IconReceipt2
                  style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-red-6)' }}
                />
                <Text fw={500}>Dépenses</Text>
              </Group>
              <Text size='xl' fw={700} c='red'>
                {formatAmount(budgetData.expensesAmount)} €
              </Text>
            </Card>
          </Grid.Col>
        </Grid>

        {/* Budget Details */}
        <Card radius='lg' shadow='sm'>
          <Card.Section inheritPadding py='md'>
            <Group justify='space-between'>
              <Group gap='xs'>
                <IconWallet
                  style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-teal-6)' }}
                />
                <Text fw={500} size='lg'>
                  Revenus
                </Text>
              </Group>
              <Badge size='lg' variant='light' color='teal'>
                {formatAmount(budgetData.incomesAmount)} €
              </Badge>
            </Group>
          </Card.Section>
          <Card.Section withBorder inheritPadding py='md'>
            <Stack gap='sm'>
              {budgetData.incomes.map((income: IncomeResponse, index: number) => (
                <Group key={index} justify='space-between' wrap='nowrap'>
                  <Text size='sm'>{income.name}</Text>
                  <DottedLine />
                  <Text size='sm' fw={500}>
                    {formatAmount(income.amount)} €
                  </Text>
                </Group>
              ))}
            </Stack>
          </Card.Section>
        </Card>

        <Card radius='lg' shadow='sm'>
          <Card.Section inheritPadding py='md'>
            <Group justify='space-between'>
              <Group gap='xs'>
                <IconReceipt2
                  style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-red-6)' }}
                />
                <Text fw={500} size='lg'>
                  Dépenses
                </Text>
              </Group>
              <Badge size='lg' variant='light' color='red'>
                {formatAmount(budgetData.expensesAmount)} €
              </Badge>
            </Group>
          </Card.Section>
          <Card.Section withBorder inheritPadding py='md'>
            <Stack gap='xl'>
              {formattedExpenses.map((category, index) => {
                const categoryTotal = calculateCategoryTotal(category.items)
                return (
                  <Stack key={index} gap='xs'>
                    <Group justify='space-between' wrap='nowrap'>
                      <Text fw={500}>{category.category}</Text>
                      <Badge color='dark' size='lg' variant='light'>
                        {formatAmount(categoryTotal)} €
                      </Badge>
                    </Group>
                    <Stack gap='xs'>
                      {category.items.map((item, itemIndex) => (
                        <Group key={itemIndex} justify='space-between' wrap='nowrap' pl='md'>
                          <Text size='sm'>{item.name}</Text>
                          <DottedLine />
                          <Text size='sm'>{formatAmount(item.amount)} €</Text>
                        </Group>
                      ))}
                    </Stack>
                  </Stack>
                )
              })}
            </Stack>
          </Card.Section>
        </Card>

        {/* Summary */}
        <Card radius='lg' shadow='sm'>
          <Card.Section inheritPadding py='md'>
            <Group gap='xs'>
              <IconPigMoney
                style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-blue-6)' }}
              />
              <Text fw={500} size='lg'>
                Résumé
              </Text>
            </Group>
          </Card.Section>
          <Card.Section withBorder inheritPadding py='md'>
            <Text size='sm'>
              Vous avez un revenu total de{' '}
              <Text span fw={500} c='teal'>
                {formatAmount(budgetData.incomesAmount)} €
              </Text>
              . Vous dépensez{' '}
              <Text span fw={500} c='red'>
                {formatAmount(budgetData.expensesAmount)} €
              </Text>{' '}
              ({calculatePercentage(budgetData.expensesAmount, budgetData.incomesAmount)}%), il vous
              reste{' '}
              <Text span fw={500} c='blue'>
                {formatAmount(budgetData.savingCapacity)} €
              </Text>{' '}
              ({calculatePercentage(budgetData.savingCapacity, budgetData.incomesAmount)}%).
            </Text>
          </Card.Section>
        </Card>

        {/* Chart Section */}
        <Card radius='lg' shadow='sm'>
          <Card.Section inheritPadding py='md'>
            <Group justify='space-between'>
              <Group gap='xs'>
                <IconChartLine
                  style={{ width: rem(20), height: rem(20), color: 'var(--mantine-color-blue-6)' }}
                />
                <Text fw={500} size='lg'>
                  Graphique des dépenses
                </Text>
              </Group>
              <Tooltip label='Ouvrir en plein écran' position='top' withArrow>
                <ActionIcon variant='light' color='blue' onClick={openChartModal} size='lg'>
                  <IconEye style={{ width: rem(20), height: rem(20) }} />
                </ActionIcon>
              </Tooltip>
            </Group>
          </Card.Section>
          <Card.Section withBorder inheritPadding py='md'>
            <Chart
              chartType='Sankey'
              width='100%'
              height={isMobile ? '200px' : '300px'}
              data={graphData}
              options={graphOptions}
            />
          </Card.Section>
        </Card>

        <Modal
          opened={chartModalOpened}
          onClose={closeChartModal}
          size='100%'
          title='Graphique des dépenses'
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
      </Stack>
    </Container>
  )
}

export default BudgetDetail
