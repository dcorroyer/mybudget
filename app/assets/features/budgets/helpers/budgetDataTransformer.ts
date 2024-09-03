const formatDateToYYYYMM = (date: Date | null): string => {
  if (!date) return ''
  const year = date.getFullYear()
  const month = String(date.getMonth() + 1).padStart(2, '0') // getMonth() returns 0-based month
  return `${year}-${month}`
}

export const budgetDataTransformer = (data: {
  date: Date | null
  incomes: { name: string; amount: number }[]
  expenses: { category: string; items: { name: string; amount: number }[] }[]
}) => {
  const newData = {
    date: formatDateToYYYYMM(data.date),
    incomes: data.incomes,
    expenses: [] as { name: string; amount: number; category: string }[],
  }

  data.expenses.forEach((category) => {
    category.items.forEach((item) => {
      newData.expenses.push({
        name: item.name,
        amount: item.amount,
        category: category.category,
      })
    })
  })

  return newData
}
