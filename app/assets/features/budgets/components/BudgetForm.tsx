import {
  useGetApiBudgetsList,
  usePostApiBudgetsCreate,
  usePutApiBudgetsUpdate,
} from '@/api/generated/budgets/budgets'
import { BudgetPayload, BudgetResponse, ExpensePayload, IncomePayload } from '@/api/models'
import { useMutationWithInvalidation } from '@/hooks/useMutation'
import { DragDropContext, Draggable, DropResult, Droppable } from '@hello-pangea/dnd'
import {
  ActionIcon,
  Box,
  Button,
  Card,
  Container,
  Grid,
  Group,
  NumberInput,
  Stack,
  Stepper,
  Text,
  TextInput,
  Title,
  rem,
} from '@mantine/core'
import { MonthPickerInput } from '@mantine/dates'
import { useForm } from '@mantine/form'
import { useViewportSize } from '@mantine/hooks'
import { notifications } from '@mantine/notifications'
import {
  IconCalendar,
  IconChevronLeft,
  IconGripVertical,
  IconPlus,
  IconReceipt2,
  IconTrash,
  IconWallet,
  IconX,
} from '@tabler/icons-react'
import React, { useEffect, useState } from 'react'
import { budgetFormSchema } from '../schemas/budgetSchema'
import { formatDateToYYYYMM, formatZodErrors, parseDateFromYYYYMM } from '../utils/budgetUtils'

interface IncomeItem {
  id: string
  name: string
  amount: number
}

interface ExpenseItem {
  id: string
  name: string
  amount: number
}

interface ExpenseCategory {
  id: string
  name: string
  items: ExpenseItem[]
}

interface FormValues {
  date: Date
  incomes: IncomePayload[]
  expenses: ExpensePayload[]
}

interface BudgetFormProps {
  initialValues?: BudgetResponse
  onClose: () => void
}

export const BudgetForm: React.FC<BudgetFormProps> = ({ initialValues, onClose }) => {
  const { width } = useViewportSize()
  const isMobile = width < 768
  const [active, setActive] = useState(0)

  const [date, setDate] = useState<Date | null>(
    initialValues?.date ? parseDateFromYYYYMM(initialValues.date) : new Date(),
  )

  const { data: budgetsData } = useGetApiBudgetsList({ page: 1, limit: 100 })

  const [incomes, setIncomes] = useState<IncomeItem[]>(
    initialValues?.incomes?.map((income, index) => ({
      id: String(index + 1),
      name: income.name,
      amount: income.amount,
    })) || [{ id: '1', name: '', amount: 0 }],
  )

  const [categories, setCategories] = useState<ExpenseCategory[]>(() => {
    if (!initialValues?.expenses) {
      return [
        {
          id: '1',
          name: '',
          items: [{ id: '1-1', name: '', amount: 0 }],
        },
      ]
    }

    const groupedExpenses = initialValues.expenses.reduce((acc, expense) => {
      const existingCategory = acc.find((cat) => cat.name === expense.category)

      if (existingCategory) {
        existingCategory.items.push({
          id: String(Math.random()),
          name: expense.name,
          amount: expense.amount,
        })
      } else {
        acc.push({
          id: String(Math.random()),
          name: expense.category,
          items: [
            {
              id: String(Math.random()),
              name: expense.name,
              amount: expense.amount,
            },
          ],
        })
      }

      return acc
    }, [] as ExpenseCategory[])

    return groupedExpenses.length > 0
      ? groupedExpenses
      : [
          {
            id: '1',
            name: '',
            items: [{ id: '1-1', name: '', amount: 0 }],
          },
        ]
  })

  const { mutate: createBudget, isPending: isCreatePending } = useMutationWithInvalidation(
    usePostApiBudgetsCreate().mutateAsync,
    {
      queryKeyToInvalidate: ['/api/budgets'],
      successMessage: 'Budget créé avec succès',
      errorMessage: 'Une erreur est survenue lors de la création du budget',
      onSuccess: onClose,
    },
  )

  const { mutate: updateBudget, isPending: isUpdatePending } = useMutationWithInvalidation(
    usePutApiBudgetsUpdate().mutateAsync,
    {
      queryKeyToInvalidate: initialValues?.id
        ? ['/api/budgets', `/api/budgets/${initialValues.id}`]
        : ['/api/budgets'],
      successMessage: 'Budget mis à jour avec succès',
      errorMessage: 'Une erreur est survenue lors de la mise à jour du budget',
      onSuccess: onClose,
    },
  )

  const isLoading = isCreatePending || isUpdatePending

  const form = useForm<FormValues>({
    initialValues: {
      date: date || new Date(),
      incomes: incomes.map(({ name, amount }) => ({ name, amount })),
      expenses: categories.flatMap((category) =>
        category.items.map((item) => ({
          name: item.name,
          amount: item.amount,
          category: category.name,
        })),
      ),
    },
  })

  useEffect(() => {
    form.setValues({
      date: date || new Date(),
      incomes: incomes.map(({ name, amount }) => ({ name, amount })),
      expenses: categories.flatMap((category) =>
        category.items.map((item) => ({
          name: item.name,
          amount: item.amount,
          category: category.name,
        })),
      ),
    })
  }, [date, incomes, categories])

  const handleSubmit = () => {
    const values = form.values
    const validationResult = budgetFormSchema.safeParse(values)

    if (!validationResult.success) {
      const formattedErrors = formatZodErrors(validationResult.error, categories)

      const ErrorMessage = () => (
        <div style={{ whiteSpace: 'pre-wrap', maxHeight: '60vh', overflow: 'auto' }}>
          <div style={{ marginBottom: '8px' }}>Veuillez corriger les erreurs suivantes :</div>
          {formattedErrors.map((error, index) => (
            <div key={index} style={{ marginBottom: '8px' }}>
              {error}
            </div>
          ))}
        </div>
      )

      notifications.show({
        title: 'Erreur de validation',
        message: <ErrorMessage />,
        color: 'red',
        autoClose: false,
      })
      return
    }

    const dateYYYYMM = formatDateToYYYYMM(values.date)
    const existingBudget = budgetsData?.data?.find(
      (budget) => budget.date === dateYYYYMM && budget.id !== initialValues?.id,
    )

    if (existingBudget) {
      notifications.show({
        title: 'Date déjà utilisée',
        message: `Un budget existe déjà pour ${new Intl.DateTimeFormat('fr-FR', { month: 'long', year: 'numeric' }).format(parseDateFromYYYYMM(dateYYYYMM))}. Veuillez choisir une autre date.`,
        color: 'red',
      })
      return
    }

    const flattenedExpenses: ExpensePayload[] = categories.flatMap((category) =>
      category.items.map((item) => ({
        name: item.name,
        amount: item.amount,
        category: category.name,
      })),
    )

    const budgetData: BudgetPayload = {
      date: new Date(formatDateToYYYYMM(values.date)),
      incomes: incomes.map(({ name, amount }) => ({ name, amount })),
      expenses: flattenedExpenses,
    }

    if (initialValues?.id) {
      updateBudget({ id: initialValues.id, data: budgetData })
    } else {
      createBudget({ data: budgetData })
    }
  }

  const onDragEnd = (result: DropResult) => {
    const { destination, source, type } = result
    if (!destination) return

    if (destination.droppableId === source.droppableId && destination.index === source.index) {
      return
    }

    if (type === 'expense') {
      const newCategories = [...categories]
      const sourceCategory = newCategories.find((c) => c.id === source.droppableId)
      const destCategory = newCategories.find((c) => c.id === destination.droppableId)

      if (!sourceCategory || !destCategory) return

      const [movedItem] = sourceCategory.items.splice(source.index, 1)

      if (source.droppableId === destination.droppableId) {
        sourceCategory.items.splice(destination.index, 0, movedItem)
      } else {
        destCategory.items.splice(destination.index, 0, { ...movedItem })
      }

      setCategories(newCategories)
    } else if (type === 'income') {
      const newIncomes = [...incomes]
      const [movedItem] = newIncomes.splice(source.index, 1)
      newIncomes.splice(destination.index, 0, movedItem)
      setIncomes(newIncomes)
    }
  }

  const addCategory = () => {
    const newCategoryId = String(categories.length + 1)
    const newItemId = `${newCategoryId}-1`
    setCategories([
      ...categories,
      {
        id: newCategoryId,
        name: '',
        items: [{ id: newItemId, name: '', amount: 0 }],
      },
    ])
  }

  const addExpense = (categoryId: string) => {
    setCategories(
      categories.map((category) => {
        if (category.id === categoryId) {
          const newItemId = `${categoryId}-${category.items.length + 1}`
          return {
            ...category,
            items: [...category.items, { id: newItemId, name: '', amount: 0 }],
          }
        }
        return category
      }),
    )
  }

  const updateExpense = (
    categoryId: string,
    itemId: string,
    field: 'name' | 'amount',
    value: string | number,
  ) => {
    setCategories(
      categories.map((category) => {
        if (category.id === categoryId) {
          return {
            ...category,
            items: category.items.map((item) => {
              if (item.id === itemId) {
                return { ...item, [field]: value }
              }
              return item
            }),
          }
        }
        return category
      }),
    )
  }

  const removeExpense = (categoryId: string, itemId: string) => {
    const updatedCategories = categories.map((category) => {
      if (category.id === categoryId) {
        const updatedItems = category.items.filter((item) => item.id !== itemId)
        return {
          ...category,
          items: updatedItems,
        }
      }
      return category
    })

    setCategories(updatedCategories.filter((category) => category.items.length > 0))
  }

  const removeCategory = (categoryId: string) => {
    setCategories(categories.filter((category) => category.id !== categoryId))
  }

  const addIncome = () => {
    const newId = String(incomes.length + 1)
    setIncomes([...incomes, { id: newId, name: '', amount: 0 }])
  }

  const updateIncome = (itemId: string, field: 'name' | 'amount', value: string | number) => {
    setIncomes(
      incomes.map((item) => {
        if (item.id === itemId) {
          return { ...item, [field]: value }
        }
        return item
      }),
    )
  }

  const removeIncome = (itemId: string) => {
    setIncomes(incomes.filter((item) => item.id !== itemId))
  }

  const nextStep = () => setActive((current) => (current < 2 ? current + 1 : current))
  const prevStep = () => setActive((current) => (current > 0 ? current - 1 : current))

  return (
    <Container size='xl' py='xl'>
      <form
        onSubmit={(e) => {
          e.preventDefault()
          if (form.validate().hasErrors) return
          handleSubmit()
        }}
      >
        <Stack gap='md'>
          {/* Header */}
          <Group justify='space-between' align='flex-end' mb='lg'>
            <Group align='center' gap='xs'>
              <ActionIcon variant='light' color='blue' onClick={onClose} size='lg'>
                <IconChevronLeft style={{ width: rem(20), height: rem(20) }} />
              </ActionIcon>
              <Stack gap={0}>
                <Title order={1} size='h2' fw={600} c='blue.7'>
                  {initialValues ? 'Édition du budget' : 'Création du budget'}
                </Title>
                <Text c='dimmed' size='sm'>
                  {initialValues ? 'Modification du budget mensuel' : 'Création du budget mensuel'}
                </Text>
              </Stack>
            </Group>
            <ActionIcon variant='light' color='blue' onClick={onClose} size='lg'>
              <IconX style={{ width: rem(20), height: rem(20) }} />
            </ActionIcon>
          </Group>

          {/* Stepper */}
          <Stepper active={active} onStepClick={setActive} allowNextStepsSelect={false}>
            <Stepper.Step label='Date' description='Date du budget'>
              <Card radius='lg' shadow='sm' mt='md'>
                <Card.Section inheritPadding py='md'>
                  <Group gap='xs'>
                    <IconCalendar
                      style={{
                        width: rem(20),
                        height: rem(20),
                        color: 'var(--mantine-color-blue-6)',
                      }}
                    />
                    <Text fw={500} size='lg'>
                      Date du budget
                    </Text>
                  </Group>
                </Card.Section>
                <Card.Section withBorder inheritPadding py='md'>
                  <Box bg='var(--mantine-color-gray-0)' p='md'>
                    <div
                      style={{
                        background: 'white',
                        padding: '8px',
                        borderRadius: '4px',
                        border: '1px solid var(--mantine-color-gray-2)',
                      }}
                    >
                      <Grid align='center'>
                        <Grid.Col span={1}>
                          <IconCalendar
                            style={{
                              width: rem(20),
                              height: rem(20),
                              color: 'var(--mantine-color-gray-5)',
                            }}
                          />
                        </Grid.Col>
                        <Grid.Col span={11}>
                          <MonthPickerInput
                            placeholder='Sélectionnez un mois'
                            value={date}
                            onChange={setDate}
                            required
                            locale='fr'
                            valueFormat='MMMM YYYY'
                            styles={{
                              input: {
                                border: 'none',
                                backgroundColor: 'transparent',
                                '&:focus': {
                                  border: 'none',
                                },
                              },
                            }}
                            mx='auto'
                            w='100%'
                          />
                        </Grid.Col>
                      </Grid>
                    </div>
                  </Box>
                </Card.Section>
              </Card>
            </Stepper.Step>

            <Stepper.Step label='Revenus' description='Sources de revenus'>
              <DragDropContext onDragEnd={onDragEnd}>
                <Card radius='lg' shadow='sm' mt='md'>
                  <Card.Section inheritPadding py='md'>
                    <Group gap='xs'>
                      <IconWallet
                        style={{
                          width: rem(20),
                          height: rem(20),
                          color: 'var(--mantine-color-teal-6)',
                        }}
                      />
                      <Text fw={500} size='lg'>
                        Revenus
                      </Text>
                    </Group>
                  </Card.Section>
                  <Card.Section withBorder inheritPadding py='md'>
                    <Droppable droppableId='incomes' type='income'>
                      {(provided) => (
                        <Box bg='var(--mantine-color-gray-0)' p='md'>
                          <Stack gap='xs' ref={provided.innerRef} {...provided.droppableProps}>
                            {incomes.map((income, index) => (
                              <Draggable key={income.id} draggableId={income.id} index={index}>
                                {(provided, snapshot) => (
                                  <div
                                    ref={provided.innerRef}
                                    {...provided.draggableProps}
                                    style={{
                                      ...provided.draggableProps.style,
                                      opacity: snapshot.isDragging ? 0.8 : 1,
                                      background: 'white',
                                      padding: '8px',
                                      borderRadius: '4px',
                                      border: '1px solid var(--mantine-color-gray-2)',
                                    }}
                                  >
                                    <Grid align='center'>
                                      <Grid.Col span={{ base: 12, sm: 1 }}>
                                        <Group
                                          gap='xs'
                                          wrap='nowrap'
                                          justify='flex-end'
                                          style={{ width: '100%' }}
                                        >
                                          {!isMobile && (
                                            <div {...provided.dragHandleProps}>
                                              <IconGripVertical
                                                style={{
                                                  width: rem(20),
                                                  height: rem(20),
                                                  color: 'var(--mantine-color-gray-5)',
                                                }}
                                              />
                                            </div>
                                          )}
                                        </Group>
                                      </Grid.Col>
                                      <Grid.Col span={{ base: 12, sm: 5 }}>
                                        <TextInput
                                          placeholder='Source de revenu'
                                          value={income.name}
                                          onChange={(e) =>
                                            updateIncome(income.id, 'name', e.target.value)
                                          }
                                          rightSection={
                                            isMobile && (
                                              <ActionIcon
                                                color='red'
                                                variant='light'
                                                onClick={() => removeIncome(income.id)}
                                                size='sm'
                                              >
                                                <IconX
                                                  style={{ width: rem(16), height: rem(16) }}
                                                />
                                              </ActionIcon>
                                            )
                                          }
                                        />
                                      </Grid.Col>
                                      <Grid.Col span={{ base: 12, sm: 5 }}>
                                        <NumberInput
                                          placeholder='Montant'
                                          value={income.amount}
                                          onChange={(value) =>
                                            updateIncome(income.id, 'amount', value || 0)
                                          }
                                          suffix=' €'
                                          hideControls
                                        />
                                      </Grid.Col>
                                      {!isMobile && (
                                        <Grid.Col span={1}>
                                          <ActionIcon
                                            color='red'
                                            variant='light'
                                            onClick={() => removeIncome(income.id)}
                                          >
                                            <IconX style={{ width: rem(16), height: rem(16) }} />
                                          </ActionIcon>
                                        </Grid.Col>
                                      )}
                                    </Grid>
                                  </div>
                                )}
                              </Draggable>
                            ))}
                            {provided.placeholder}
                            <Button
                              variant='light'
                              leftSection={<IconPlus size={16} />}
                              onClick={addIncome}
                              fullWidth
                            >
                              Ajouter un revenu
                            </Button>
                          </Stack>
                        </Box>
                      )}
                    </Droppable>
                  </Card.Section>
                </Card>
              </DragDropContext>
            </Stepper.Step>

            <Stepper.Step label='Dépenses' description='Catégories et montants'>
              <DragDropContext onDragEnd={onDragEnd}>
                <Card radius='lg' shadow='sm' mt='md'>
                  <Card.Section inheritPadding py='md'>
                    <Group gap='xs'>
                      <IconReceipt2
                        style={{
                          width: rem(20),
                          height: rem(20),
                          color: 'var(--mantine-color-red-6)',
                        }}
                      />
                      <Text fw={500} size='lg'>
                        Dépenses
                      </Text>
                    </Group>
                  </Card.Section>
                  <Card.Section withBorder inheritPadding py='md'>
                    <Stack gap='xl'>
                      {categories.map((category) => (
                        <div key={category.id}>
                          <Group justify='space-between' mb='xs'>
                            <TextInput
                              placeholder='Nom de la catégorie'
                              value={category.name}
                              onChange={(e) => {
                                setCategories(
                                  categories.map((c) =>
                                    c.id === category.id ? { ...c, name: e.target.value } : c,
                                  ),
                                )
                              }}
                              styles={{
                                input: {
                                  border: 'none',
                                  backgroundColor: 'transparent',
                                  fontSize: '1.1rem',
                                  fontWeight: 600,
                                  color: 'var(--mantine-color-blue-7)',
                                  cursor: 'text',
                                  paddingRight: isMobile ? '3rem' : '2rem',
                                  transition: 'all 0.2s',
                                  '&:hover, &:focus': {
                                    backgroundColor: 'var(--mantine-color-blue-0)',
                                  },
                                  '&::placeholder': {
                                    color: 'var(--mantine-color-gray-5)',
                                  },
                                },
                                root: {
                                  width: '100%',
                                },
                              }}
                              rightSection={
                                isMobile ? (
                                  <ActionIcon
                                    color='red'
                                    variant='light'
                                    onClick={() => removeCategory(category.id)}
                                    size='sm'
                                  >
                                    <IconTrash style={{ width: rem(16), height: rem(16) }} />
                                  </ActionIcon>
                                ) : (
                                  <ActionIcon
                                    color='red'
                                    variant='light'
                                    onClick={() => removeCategory(category.id)}
                                  >
                                    <IconTrash style={{ width: rem(16), height: rem(16) }} />
                                  </ActionIcon>
                                )
                              }
                            />
                          </Group>

                          <Droppable droppableId={category.id} type='expense'>
                            {(provided) => (
                              <Box bg='var(--mantine-color-gray-0)' p='md'>
                                <Stack
                                  gap='xs'
                                  ref={provided.innerRef}
                                  {...provided.droppableProps}
                                >
                                  {category.items.map((item, index) => (
                                    <Draggable key={item.id} draggableId={item.id} index={index}>
                                      {(provided, snapshot) => (
                                        <div
                                          ref={provided.innerRef}
                                          {...provided.draggableProps}
                                          style={{
                                            ...provided.draggableProps.style,
                                            opacity: snapshot.isDragging ? 0.8 : 1,
                                            background: 'white',
                                            padding: '8px',
                                            borderRadius: '4px',
                                            border: '1px solid var(--mantine-color-gray-2)',
                                          }}
                                        >
                                          <Grid align='center'>
                                            <Grid.Col span={{ base: 12, sm: 1 }}>
                                              <Group
                                                gap='xs'
                                                wrap='nowrap'
                                                justify='flex-end'
                                                style={{ width: '100%' }}
                                              >
                                                {!isMobile && (
                                                  <div {...provided.dragHandleProps}>
                                                    <IconGripVertical
                                                      style={{
                                                        width: rem(20),
                                                        height: rem(20),
                                                        color: 'var(--mantine-color-gray-5)',
                                                      }}
                                                    />
                                                  </div>
                                                )}
                                              </Group>
                                            </Grid.Col>
                                            <Grid.Col span={{ base: 12, sm: 5 }}>
                                              <TextInput
                                                placeholder='Nom de la dépense'
                                                value={item.name}
                                                onChange={(e) =>
                                                  updateExpense(
                                                    category.id,
                                                    item.id,
                                                    'name',
                                                    e.target.value,
                                                  )
                                                }
                                                rightSection={
                                                  isMobile && (
                                                    <ActionIcon
                                                      color='red'
                                                      variant='light'
                                                      onClick={() =>
                                                        removeExpense(category.id, item.id)
                                                      }
                                                      size='sm'
                                                    >
                                                      <IconX
                                                        style={{ width: rem(16), height: rem(16) }}
                                                      />
                                                    </ActionIcon>
                                                  )
                                                }
                                              />
                                            </Grid.Col>
                                            <Grid.Col span={{ base: 12, sm: 5 }}>
                                              <NumberInput
                                                placeholder='Montant'
                                                value={item.amount}
                                                onChange={(value) =>
                                                  updateExpense(
                                                    category.id,
                                                    item.id,
                                                    'amount',
                                                    value || 0,
                                                  )
                                                }
                                                suffix=' €'
                                                hideControls
                                              />
                                            </Grid.Col>
                                            {!isMobile && (
                                              <Grid.Col span={1}>
                                                <ActionIcon
                                                  color='red'
                                                  variant='light'
                                                  onClick={() =>
                                                    removeExpense(category.id, item.id)
                                                  }
                                                >
                                                  <IconX
                                                    style={{ width: rem(16), height: rem(16) }}
                                                  />
                                                </ActionIcon>
                                              </Grid.Col>
                                            )}
                                          </Grid>
                                        </div>
                                      )}
                                    </Draggable>
                                  ))}
                                  {provided.placeholder}
                                  <Button
                                    variant='light'
                                    leftSection={<IconPlus size={16} />}
                                    onClick={() => addExpense(category.id)}
                                    fullWidth
                                  >
                                    Ajouter une dépense
                                  </Button>
                                </Stack>
                              </Box>
                            )}
                          </Droppable>
                        </div>
                      ))}
                      <Group justify='center'>
                        <Button
                          variant='light'
                          leftSection={<IconPlus size={16} />}
                          onClick={addCategory}
                        >
                          Ajouter une catégorie
                        </Button>
                      </Group>
                    </Stack>
                  </Card.Section>
                </Card>
              </DragDropContext>
            </Stepper.Step>
          </Stepper>

          {/* Actions */}
          <Group
            justify='flex-end'
            mt='xl'
            style={{
              flexDirection: isMobile ? 'column' : 'row',
              gap: isMobile ? '0.5rem' : undefined,
            }}
          >
            {active > 0 && (
              <Button variant='light' onClick={prevStep} fullWidth={isMobile}>
                Retour
              </Button>
            )}
            {active < 2 ? (
              <Button onClick={nextStep} fullWidth={isMobile}>
                Suivant
              </Button>
            ) : (
              <>
                <Button variant='light' color='red' onClick={onClose} fullWidth={isMobile}>
                  Annuler
                </Button>
                <Button color='blue' type='submit' loading={isLoading} fullWidth={isMobile}>
                  {initialValues ? 'Mettre à jour' : 'Créer'}
                </Button>
              </>
            )}
          </Group>
        </Stack>
      </form>
    </Container>
  )
}
