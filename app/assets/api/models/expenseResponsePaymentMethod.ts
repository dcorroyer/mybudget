/**
 * Generated by orval v7.6.0 🍺
 * Do not edit manually.
 * MyBudget API
 * API for budget and savings management
 * OpenAPI spec version: 1.0.0
 */

/**
 * Payment method
 */
export type ExpenseResponsePaymentMethod =
  (typeof ExpenseResponsePaymentMethod)[keyof typeof ExpenseResponsePaymentMethod]

// eslint-disable-next-line @typescript-eslint/no-redeclare
export const ExpenseResponsePaymentMethod = {
  OTHER: 'OTHER',
  BILLS_ACCOUNT: 'BILLS_ACCOUNT',
  BANK_TRANSFER: 'BANK_TRANSFER',
} as const
