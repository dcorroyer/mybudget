import React from 'react'
import { useNavigate } from 'react-router-dom'

import { BudgetForm } from '../components/budget-form'

const BudgetCreate: React.FC = () => {
  const navigate = useNavigate()

  return <BudgetForm onClose={() => navigate('/budgets')} />
}

export default BudgetCreate
