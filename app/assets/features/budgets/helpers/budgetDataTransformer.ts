export const budgetDataTransformer = (data: {
  date: string
  incomes: { name: string; amount: number }[]
  expenses: { category: string; items: { name: string; amount: number }[] }[]
}) => {
  const newData = {
    incomes: data.incomes,
    expenses: [] as { name: string; amount: number; category: string }[],
  }

  data.expenses.forEach((category) => {
    category.items.forEach((item) => {
      return newData.expenses.push({
        name: item.name,
        amount: item.amount,
        category: category.category,
      })
    })
  })

  return newData
}
