const formatDateToYYYYMM = (date: Date | null): string => {
  if (!date) return ''
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0')
  return `${year}-${month}`
}

export const budgetDataTransformer = (data: {
  date: Date
  incomes: { id?: number | undefined; name: string; amount: number }[]
  expenses: {
    category: string
    items: { id?: number | undefined; name: string; amount: number }[]
  }[]
}) => {
  const newData = {
    date: formatDateToYYYYMM(data.date),
    incomes: data.incomes,
    expenses: [] as { id: number; name: string; amount: number; category: string }[],
  }

  data.expenses.forEach((category) => {
    category.items.forEach((item) => {
      newData.expenses.push({
        id: item.id ?? 0,
        name: item.name,
        amount: item.amount,
        category: category.category,
      })
    })
  })

  return newData
}

export const groupExpensesByCategory = (
  expenses: { id: number; name: string; amount: number; category: string }[],
) => {
  const groupedExpenses: { [key: string]: { id: number; name: string; amount: number }[] } = {}

  expenses.forEach((expense) => {
    if (!groupedExpenses[expense.category]) {
      groupedExpenses[expense.category] = []
    }
    groupedExpenses[expense.category].push({
      id: expense.id,
      name: expense.name,
      amount: expense.amount,
    })
  })

  return Object.keys(groupedExpenses).map((category) => ({
    category,
    items: groupedExpenses[category],
  }))
}
