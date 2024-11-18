export interface Transaction {
  id: number
  description: string
  amount: number
  type: 'CREDIT' | 'DEBIT'
  date: string
} 