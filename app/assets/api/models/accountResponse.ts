/**
 * Generated by orval v7.6.0 🍺
 * Do not edit manually.
 * MyBudget API
 * API for budget and savings management
 * OpenAPI spec version: 1.0.0
 */

/**
 * Account data
 */
export interface AccountResponse {
  /** Unique account identifier */
  id: number
  /** Account name */
  name: string
  /** Account type */
  type: string
  /** Account balance */
  balance: number
}
