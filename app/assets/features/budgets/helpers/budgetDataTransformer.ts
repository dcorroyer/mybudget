export const budgetDataTransformer = (data) => {
  const newJson = {
    incomes: data.incomes,
    expenses: [],
  }

  data.expenses.forEach((category) => {
    category.items.forEach((item) => {
      newJson.expenses.push({
        name: item.name,
        amount: item.amount,
        category: category.category,
      })
    })
  })

  return newJson
}
