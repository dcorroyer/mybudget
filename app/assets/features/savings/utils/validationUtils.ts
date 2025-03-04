import { z } from 'zod'

/**
 * Formate les erreurs de validation Zod pour le formulaire de transaction
 */
export const formatTransactionZodErrors = (error: z.ZodError): string[] => {
  return error.errors.map((err) => {
    const fieldPath = err.path.join('.')
    let fieldName = ''

    if (fieldPath === 'description') {
      fieldName = 'Description'
    } else if (fieldPath === 'amount') {
      fieldName = 'Montant'
    } else if (fieldPath === 'type') {
      fieldName = 'Type de transaction'
    } else if (fieldPath === 'date') {
      fieldName = 'Date'
    } else if (fieldPath === 'account.id') {
      fieldName = 'Compte'
    } else if (fieldPath === 'account.name') {
      fieldName = 'Nom du compte'
    } else {
      fieldName = fieldPath
    }

    return `• ${fieldName}: ${err.message}`
  })
}

/**
 * Formate les erreurs de validation Zod pour le formulaire de compte
 */
export const formatAccountZodErrors = (error: z.ZodError): string[] => {
  return error.errors.map((err) => {
    const fieldPath = err.path.join('.')
    let fieldName = ''

    if (fieldPath === 'name') {
      fieldName = 'Nom du compte'
    } else if (fieldPath === 'id') {
      fieldName = 'Identifiant du compte'
    } else {
      fieldName = fieldPath
    }

    return `• ${fieldName}: ${err.message}`
  })
}
