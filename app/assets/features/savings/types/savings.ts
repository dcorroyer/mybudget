export interface SavingsBalance {
  date: string
  balance: number
}

export interface SavingsAccount {
  id: number
  name: string
}

export interface SavingsResponse {
  accounts: SavingsAccount[]
  balances: SavingsBalance[]
}

export interface SavingsFilterParams {
  accountIds?: number[]
  period?: '3' | '6' | '12'
} 
