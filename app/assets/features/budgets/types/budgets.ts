export interface Budget {
  id: number
  name: string
  date: Date
  savingCapacity: number
  incomesAmount: number
  expensesAmount: number
}

export interface BudgetDetails extends Budget {
  incomes: {
    id: number
    name: string
    amount: number
  }[]
  expenses: {
    id: number
    category: string
    name: string
    amount: number
  }[]
}

export interface BudgetFormDetails extends Budget {
  incomes: {
    id: number
    name: string
    amount: number
  }[]
  expenses: {
    category: string
    items: {
      id: number
      name: string
      amount: number
    }[]
  }[]
}

export type BudgetParams = {
  date: string
  incomes: { name: string; amount: number }[]
  expenses: { category: string; name: string; amount: number }[]
}
