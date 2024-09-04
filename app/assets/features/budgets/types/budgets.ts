export interface Budget {
  id: number
  name: string
  date: string
  savingCapacity: number
  incomesAmount: number
  expensesAmount: number
}

export interface BudgetDetails extends Budget {
  incomes: [
    {
      id: number
      name: string
      amount: number
    },
  ]
  expenses: [
    {
      id: number
      name: string
      amount: number
    },
  ]
}

export type BudgetParams = {
  date: string
  incomes: { name: string; amount: number }[]
  expenses: { category: string; name: string; amount: number }[]
}
