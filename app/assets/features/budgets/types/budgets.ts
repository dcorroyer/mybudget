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
