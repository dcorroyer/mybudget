import { formTypeCreateBudget } from '@/schemas'

export async function postBudget(values: formTypeCreateBudget, token: string): Promise<Response> {
    return await fetch('api/budgets', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify({
            date: '2023-01',
            incomes: values.incomes,
            expenses: values.expenses,
        }),
    })
}
