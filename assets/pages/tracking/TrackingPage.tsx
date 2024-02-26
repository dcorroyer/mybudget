import React from 'react'
import {
    FormTypeCreateBudget,
    useFormContextCreateBudget,
    useFormCreateBudget,
} from '@/schemas/budget'
import { useFieldArray } from 'react-hook-form'
import { Form, FormControl, FormField, FormItem, FormMessage } from '@/components/ui/form'
import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { DeleteIcon } from 'lucide-react'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'

export default function TrackingPage() {
    const {
        formState: { errors },
        watch,
        control,
        handleSubmit,
    } = useFormContextCreateBudget()

    const form = useFormCreateBudget()

    const formData = watch()
    console.log('formData, errors', formData, errors)

    const { append, remove, fields } = useFieldArray({ name: 'categories', control })

    const onSubmit = (data: FormTypeCreateBudget) => {
        console.log('data', data)
    }

    return (
        <div className='flex flex-col items-center py-12 sm:px-6 lg:px-8'>
            <Form {...form}>
                <form onSubmit={handleSubmit(onSubmit)} className='space-y-8'>
                    <div className='w-full max-w-md space-y-4'>
                        {fields.map((category, categoryIndex) => {
                            return (
                                <Card key={category.id} className='space-y-4'>
                                    <CardHeader className='flex items-center justify-between'>
                                        <div className='flex items-center'>
                                            <FormField
                                                control={form.control}
                                                key={category.id}
                                                name={`categories.${categoryIndex}.name`}
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
                                                                errors?.categories?.[categoryIndex]
                                                                    ?.name?.message
                                                            }
                                                        />
                                                    </FormItem>
                                                )}
                                            />
                                            <DeleteIcon
                                                onClick={() => {
                                                    remove(categoryIndex)
                                                }}
                                                className='cursor-pointer hover:text-red-400'
                                            />
                                        </div>
                                    </CardHeader>
                                    <CardContent>
                                        <ManageExpenseLines categoryIndex={categoryIndex} />
                                        <div className='text-red-600 text-sm mt-1'>
                                            {
                                                /* Error: Category expenseLines */
                                                errors?.categories?.[categoryIndex]?.expenseLines
                                                    ?.message
                                            }
                                        </div>
                                    </CardContent>
                                </Card>
                            )
                        })}
                        <Button
                            onClick={() => {
                                append({ expenseLines: [{ name: '', amount: 0 }], name: '' })
                            }}
                            variant='ghost'
                            className='text-center w-full underline underline-offset-4 py-2'
                        >
                            add category
                        </Button>
                    </div>
                    <Button className='px-4 py-2 rounded-lg' type='submit' variant='ghost'>
                        Submit
                    </Button>
                </form>
            </Form>
        </div>
    )
}

const ManageExpenseLines = ({ categoryIndex }: { categoryIndex: number }) => {
    const {
        register,
        formState: { errors },
        watch,
        control,
    } = useFormContextCreateBudget()

    const formData = watch()
    console.log('formData, errors', formData, errors)

    const { append, remove, fields } = useFieldArray({
        name: `categories.${categoryIndex}.expenseLines`,
        control,
    })

    return (
        <div className=' space-y-4'>
            {fields.map((expenseLine, expenseLineIndex) => {
                return (
                    <div key={expenseLine.id} className='py-4 bg-white p-6 rounded-lg shadow-xl'>
                        <div>
                            <div className='flex justify-between'>
                                <div className='mb-2 font-semibold'>Note</div>
                                <button
                                    type='button'
                                    onClick={() => {
                                        remove(expenseLineIndex)
                                    }}
                                    className='text-red-400 text-xs underline underline-offset-4'
                                >
                                    Remove expense
                                </button>
                            </div>
                            <label title={'Name'} className='inline-block'>
                                <div className='mb-1'>Name</div>
                                <input
                                    placeholder='Enter name'
                                    className='border-2 border-gray-600 rounded-lg px-2 py-1 bg-transparent'
                                    {...register(
                                        `categories.${categoryIndex}.expenseLines.${expenseLineIndex}.name`,
                                    )}
                                />
                                <div className='text-red-600'>
                                    {
                                        /* Error: Chapter notes content */
                                        errors?.categories?.[categoryIndex]?.expenseLines?.[
                                            expenseLineIndex
                                        ]?.name?.message
                                    }
                                </div>
                            </label>
                            <label title={'Amount'} className='inline-block'>
                                <div className='mb-1'>Amount</div>
                                <input
                                    placeholder='Enter amount'
                                    className='border-2 border-gray-600 rounded-lg px-2 py-1 bg-transparent'
                                    {...register(
                                        `categories.${categoryIndex}.expenseLines.${expenseLineIndex}.amount`,
                                    )}
                                />
                                <div className='text-red-600'>
                                    {
                                        /* Error: Chapter notes content */
                                        errors?.categories?.[categoryIndex]?.expenseLines?.[
                                            expenseLineIndex
                                        ]?.amount?.message
                                    }
                                </div>
                            </label>
                        </div>
                    </div>
                )
            })}
            <button
                type='button'
                onClick={() => {
                    append({ name: '', amount: 0 })
                }}
                className='text-gray-600 text-center w-full underline underline-offset-4 py-2'
            >
                add expense
            </button>
        </div>
    )
}
