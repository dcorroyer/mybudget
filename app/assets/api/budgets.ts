import { FormTypeCreateBudget } from '@/schemas'

export async function postBudget(values: FormTypeCreateBudget, token: string): Promise<Response> {
    return await fetch('api/budgets', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({
            incomes: values.incomes,
            expenses: values.expenses,
        }),
    })
}
