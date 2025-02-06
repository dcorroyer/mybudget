export interface Transaction {
  id: number
  description: string
  amount: number
  type: 'CREDIT' | 'DEBIT'
  date: Date
  account: {
    id: number
    name: string
  }
}

export interface TransactionParams {
  description: string
  amount: number
  type: 'CREDIT' | 'DEBIT'
  date: Date
}

export interface TransactionFilterParams {
  accountIds?: number[]
  page?: number
  perPage?: number
}
