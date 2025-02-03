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
import React, { useState } from 'react'

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

interface BudgetFormProps {
  initialValues?: any
  onClose: () => void
}

export const BudgetForm: React.FC<BudgetFormProps> = ({ initialValues, onClose }) => {
  const [active, setActive] = useState(0)
  const [date, setDate] = useState<Date | null>(new Date())
  const [incomes, setIncomes] = useState<IncomeItem[]>([{ id: '1', name: 'Salaire', amount: 2000 }])
  const [categories, setCategories] = useState<ExpenseCategory[]>([
    {
      id: '1',
      name: 'Habitation',
      items: [
        { id: '1-1', name: 'Loyer', amount: 800 },
        { id: '1-2', name: 'Électricité', amount: 150 },
      ],
    },
    {
      id: '2',
      name: 'Abonnements',
      items: [
        { id: '2-1', name: 'Internet', amount: 150 },
        { id: '2-2', name: 'Mobile', amount: 100 },
      ],
    },
  ])

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
        name: 'Nouvelle catégorie',
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

    // Supprimer la catégorie si elle n'a plus d'items
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
      <Stack gap='md'>
        {/* Header */}
        <Group justify='space-between' align='flex-end' mb='lg'>
          <Group align='center' gap='xs'>
            <ActionIcon variant='light' color='blue' onClick={onClose} size='lg'>
              <IconChevronLeft style={{ width: rem(20), height: rem(20) }} />
            </ActionIcon>
            <Stack gap={0}>
              <Title order={1} size='h2' fw={600} c='blue.7'>
                Édition du budget
              </Title>
              <Text c='dimmed' size='sm'>
                Modification du budget mensuel
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
                                    <Grid.Col span={1}>
                                      <div {...provided.dragHandleProps}>
                                        <IconGripVertical
                                          style={{
                                            width: rem(20),
                                            height: rem(20),
                                            color: 'var(--mantine-color-gray-5)',
                                          }}
                                        />
                                      </div>
                                    </Grid.Col>
                                    <Grid.Col span={5}>
                                      <TextInput
                                        placeholder='Source de revenu'
                                        value={income.name}
                                        onChange={(e) =>
                                          updateIncome(income.id, 'name', e.target.value)
                                        }
                                      />
                                    </Grid.Col>
                                    <Grid.Col span={5}>
                                      <NumberInput
                                        placeholder='Montant'
                                        value={income.amount}
                                        onChange={(value) =>
                                          updateIncome(income.id, 'amount', value || 0)
                                        }
                                        suffix=' €'
                                      />
                                    </Grid.Col>
                                    <Grid.Col span={1}>
                                      <Group justify='end'>
                                        <ActionIcon
                                          color='red'
                                          variant='light'
                                          onClick={() => removeIncome(income.id)}
                                        >
                                          <IconX style={{ width: rem(16), height: rem(16) }} />
                                        </ActionIcon>
                                      </Group>
                                    </Grid.Col>
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
                                paddingRight: '2rem',
                                transition: 'all 0.2s',
                                '&:hover, &:focus': {
                                  backgroundColor: 'var(--mantine-color-blue-0)',
                                },
                                '&::placeholder': {
                                  color: 'var(--mantine-color-gray-5)',
                                },
                              },
                              wrapper: {
                                width: '300px',
                                position: 'relative',
                                '&::after': {
                                  content: '""',
                                  position: 'absolute',
                                  right: '0.5rem',
                                  top: '50%',
                                  transform: 'translateY(-50%)',
                                  width: '1rem',
                                  height: '1rem',
                                  backgroundImage:
                                    "url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'%3E%3C/path%3E%3C/svg%3E\")",
                                  backgroundRepeat: 'no-repeat',
                                  backgroundSize: 'contain',
                                  opacity: 0.5,
                                },
                                '&:hover::after': {
                                  opacity: 1,
                                },
                              },
                            }}
                          />
                          <ActionIcon
                            color='red'
                            variant='light'
                            onClick={() => removeCategory(category.id)}
                          >
                            <IconTrash style={{ width: rem(16), height: rem(16) }} />
                          </ActionIcon>
                        </Group>

                        <Droppable droppableId={category.id} type='expense'>
                          {(provided) => (
                            <Box bg='var(--mantine-color-gray-0)' p='md'>
                              <Stack gap='xs' ref={provided.innerRef} {...provided.droppableProps}>
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
                                          <Grid.Col span={1}>
                                            <div {...provided.dragHandleProps}>
                                              <IconGripVertical
                                                style={{
                                                  width: rem(20),
                                                  height: rem(20),
                                                  color: 'var(--mantine-color-gray-5)',
                                                }}
                                              />
                                            </div>
                                          </Grid.Col>
                                          <Grid.Col span={5}>
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
                                            />
                                          </Grid.Col>
                                          <Grid.Col span={5}>
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
                                            />
                                          </Grid.Col>
                                          <Grid.Col span={1}>
                                            <Group justify='end'>
                                              <ActionIcon
                                                color='red'
                                                variant='light'
                                                onClick={() => removeExpense(category.id, item.id)}
                                              >
                                                <IconX
                                                  style={{ width: rem(16), height: rem(16) }}
                                                />
                                              </ActionIcon>
                                            </Group>
                                          </Grid.Col>
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
        <Group justify='flex-end' mt='xl'>
          {active > 0 && (
            <Button variant='light' onClick={prevStep}>
              Retour
            </Button>
          )}
          {active < 2 ? (
            <Button onClick={nextStep}>Suivant</Button>
          ) : (
            <>
              <Button variant='light' color='red' onClick={onClose}>
                Annuler
              </Button>
              <Button color='blue' onClick={onClose}>
                Enregistrer
              </Button>
            </>
          )}
        </Group>
      </Stack>
    </Container>
  )
}
