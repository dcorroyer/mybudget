import React from 'react'
import { useNavigate } from 'react-router-dom'

import { BudgetForm } from '../components/BudgetForm'

const BudgetCreate = () => {
  const navigate = useNavigate()

  return <BudgetForm onClose={() => navigate('/budgets')} />
}

export default BudgetCreate
