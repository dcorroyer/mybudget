/**
 * Generated by orval v7.6.0 🍺
 * Do not edit manually.
 * MyBudget API
 * API for budget and savings management
 * OpenAPI spec version: 1.0.0
 */
import type { TransactionPayloadType } from './transactionPayloadType'

/**
 * Data for creating or updating a transaction
 */
export interface TransactionPayload {
  /** Transaction description */
  description: string
  /**
   * Transaction amount
   * @minimum 0
   * @exclusiveMinimum
   */
  amount: number
  /** Transaction type */
  type: TransactionPayloadType
  /** Transaction date */
  date: Date
}
