import React from 'react'
import { useFieldArray, useForm, useWatch } from 'react-hook-form'

import { formTypeCreateBudget, budgetFormSchema } from '@/schemas/budget'

import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form'

import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { InputSuffixIn } from '@/components/ui/input-suffix-in'

import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'

import { DeleteIcon, EuroIcon, XIcon } from 'lucide-react'
import { zodResolver } from '@hookform/resolvers/zod'
import { postBudget } from '@/api';

export default function BudgetPage(): React.JSX.Element {
    const form = useForm<formTypeCreateBudget>({
        resolver: zodResolver(budgetFormSchema),
        defaultValues: {
            incomes: [
                {
                    name: '',
                    amount: 0,
                },
            ],
            expenses: [
                {
                    categoryName: '',
                    expenseLines: [
                        {
                            name: '',
                            amount: 0,
                        },
                    ],
                },
            ],
        },
    })

    async function onSubmit(data: formTypeCreateBudget): Promise<void> {
        try {
            const response = await postBudget(data)

            if (!response.ok) {
                throw new Error('Failed to register the budget')
            }
        } catch (error) {
            console.log('Error logging in:', error)
        }
    }

    return (
        <div className='flex flex-col items-center py-12 sm:px-6 lg:px-8 max-w-screen-lg mx-auto'>
            <Form {...form}>
                <form onSubmit={form.handleSubmit(onSubmit)} className='space-y-8'>
                    <Tabs defaultValue='income' className='w-full max-w-screen-md'>
                        <TabsList className='flex space-x-1'>
                            <TabsTrigger value='income' className='w-1/2'>
                                Incomes
                            </TabsTrigger>
                            <TabsTrigger value='expense' className='w-1/2'>
                                Expenses
                            </TabsTrigger>
                        </TabsList>
                        <TabsContent value='income'>
                            <ManageIncomes />
                        </TabsContent>
                        <TabsContent value='expense'>
                            <ManageExpenses />
                        </TabsContent>
                    </Tabs>
                </form>
            </Form>
        </div>
    )
}

const ManageIncomes = () => {
    const incomes = useWatch({ name: 'incomes' })
    const { append, remove, fields } = useFieldArray({
        name: 'incomes',
    })

    return (
        <div className='space-y-2 py-6 px-4 sm:px-0'>
            {fields.map((income, incomeIndex) => {
                return (
                    <div key={income.id}>
                        <Card>
                            <CardHeader>
                                <div className='flex items-center space-x-2 relative'>
                                    <FormField
                                        control={incomes.control}
                                        name={`incomes.${incomeIndex}.name`}
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormLabel>Name</FormLabel>
                                                <FormControl>
                                                    <Input placeholder='Name' {...field} />
                                                </FormControl>
                                                <FormMessage
                                                    content={
                                                        incomes.errors?.incomes?.[incomeIndex]?.name
                                                            ?.message
                                                    }
                                                />
                                            </FormItem>
                                        )}
                                    />
                                    <FormField
                                        control={incomes.control}
                                        name={`incomes.${incomeIndex}.amount`}
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormLabel>Amount</FormLabel>
                                                <FormControl>
                                                    <InputSuffixIn
                                                        {...field}
                                                        suffix={<EuroIcon />}
                                                    />
                                                </FormControl>
                                                <FormMessage
                                                    content={
                                                        incomes.errors?.incomes?.[incomeIndex]
                                                            ?.amount?.message
                                                    }
                                                />
                                            </FormItem>
                                        )}
                                    />
                                    <XIcon
                                        onClick={() => {
                                            remove(incomeIndex)
                                        }}
                                        className='cursor-pointer hover:text-red-400 absolute top-2 -translate-y-1 right-0'
                                    />
                                </div>
                            </CardHeader>
                        </Card>
                    </div>
                )
            })}
            <Button
                type='button'
                variant='ghost'
                onClick={() => {
                    append({ name: '', amount: 0 })
                }}
                className='text-gray-600 text-center w-full underline underline-offset-4 py-2'
            >
                add income
            </Button>
        </div>
    )
}

const ManageExpenses = () => {
    const expenses = useWatch({ name: 'expenses' })
    const { append, remove, fields } = useFieldArray({
        name: 'expenses',
    })

    return (
        <div className='space-y-2 py-6 px-4 sm:px-0'>
            {fields.map((expense, expenseIndex) => {
                return (
                    <div key={expense.id}>
                        <Card className='space-y-4'>
                            <CardHeader>
                                <div className='flex items-center relative'>
                                    <FormField
                                        control={expenses.control}
                                        name={`expenses.${expenseIndex}.categoryName`}
                                        defaultValue={''}
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormControl>
                                                    <Input
                                                        placeholder='Enter category name...'
                                                        {...field}
                                                    />
                                                </FormControl>
                                                <FormMessage
                                                    content={
                                                        expenses.errors?.expenses?.[expenseIndex]
                                                            ?.categoryName?.message
                                                    }
                                                />
                                            </FormItem>
                                        )}
                                    />
                                    {fields.length > 1 && (
                                        <DeleteIcon
                                            onClick={() => {
                                                remove(expenseIndex)
                                            }}
                                            className='cursor-pointer hover:text-red-400 absolute right-0'
                                        />
                                    )}
                                </div>
                            </CardHeader>
                            <CardContent>
                                <ManageExpenseLines expenseIndex={expenseIndex} />
                                <div className='text-red-600 text-sm mt-1'>
                                    {
                                        /* Error: Category expenseLines */
                                        expenses.errors?.expenses?.[expenseIndex]?.expenseLines
                                            ?.message
                                    }
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                )
            })}
            <Button
                type='button'
                onClick={() => {
                    append({ expenseLines: [{ name: '', amount: 0 }], categoryName: '' })
                }}
                variant='ghost'
                className='text-center w-full underline underline-offset-4 py-2'
            >
                add category
            </Button>
            <Button className='px-4 py-2 rounded-lg' type='submit' variant='ghost'>
                Submit
            </Button>
        </div>
    )
}

const ManageExpenseLines = ({ expenseIndex }: { expenseIndex: number }) => {
    const expenseLines = useWatch({ name: `expenses.${expenseIndex}.expenseLines` })
    const { append, remove, fields } = useFieldArray({
        name: `expenses.${expenseIndex}.expenseLines`,
    })

    return (
        <div className='space-y-4'>
            {fields.map((expenseLine, expenseLineIndex) => {
                return (
                    <div key={expenseLine.id} className='flex items-center space-x-2 relative'>
                        <FormField
                            control={expenseLines.control}
                            name={`expenses.${expenseIndex}.expenseLines.${expenseLineIndex}.name`}
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Name</FormLabel>
                                    <FormControl>
                                        <Input placeholder='Name' {...field} />
                                    </FormControl>
                                    <FormMessage
                                        content={
                                            expenseLines.errors?.expenses?.[expenseIndex]
                                                ?.expenseLines?.[expenseLineIndex]?.name?.message
                                        }
                                    />
                                </FormItem>
                            )}
                        />
                        <FormField
                            control={expenseLines.control}
                            name={`expenses.${expenseIndex}.expenseLines.${expenseLineIndex}.amount`}
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Amount</FormLabel>
                                    <FormControl>
                                        <InputSuffixIn {...field} suffix={<EuroIcon />} />
                                    </FormControl>
                                    <FormMessage
                                        content={
                                            expenseLines.errors?.expenses?.[expenseIndex]
                                                ?.expenseLines?.[expenseLineIndex]?.amount?.message
                                        }
                                    />
                                </FormItem>
                            )}
                        />
                        <XIcon
                            onClick={() => {
                                remove(expenseLineIndex)
                            }}
                            className='cursor-pointer hover:text-red-400 absolute top-2 -translate-y-1 right-0'
                        />
                    </div>
                )
            })}
            <Button
                type='button'
                variant='ghost'
                onClick={() => {
                    append({ name: '', amount: 0 })
                }}
                className='text-gray-600 text-center w-full underline underline-offset-4 py-2'
            >
                add expense
            </Button>
        </div>
    )
}
