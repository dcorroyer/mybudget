import React from 'react'
import { useFormContextCreateBudget } from '@/schemas/budget'
import { useFieldArray } from 'react-hook-form'

export default function TrackingPage(): React.JSX.Element {
    const {
        formState: { errors },
        watch,
        handleSubmit,
    } = useFormContextCreateBudget()

    const formData = watch()
    console.log('formData, errors', formData, errors)

    return (
        <div className='flex flex-col items-center py-12 sm:px-6 lg:px-8'>
            <div className='p-6 bg-gray-300 shadow-sm rounded-lg'>
                <div className='text-xl mb-2 font-bold'>New Budget</div>
                <form
                    onSubmit={handleSubmit((data) => {
                        console.log('Form submitted', data)
                    })}
                    className='space-y-3'
                >
                    <ManageCategories />
                    <button className='px-4 py-2 bg-blue-600 rounded-lg text-white' type='submit'>
                        Submit
                    </button>
                </form>
            </div>
        </div>
    )
}

const ManageCategories = () => {
    const {
        register,
        formState: { errors },
        watch,
        control,
    } = useFormContextCreateBudget()

    const formData = watch()
    console.log('formData, errors', formData, errors)

    const { append, remove, fields } = useFieldArray({ name: 'categories', control })

    return (
        <div className=' space-y-4 '>
            {fields.map((category, categoryIndex) => {
                return (
                    <div
                        key={category.id}
                        className='p-6 bg-gray-200 shadow-lg rounded-lg space-y-3'
                    >
                        <div className='flex justify-between'>
                            <div className='text-lg mb-2 font-semibold'>Categories</div>
                            <button
                                type='button'
                                onClick={() => {
                                    remove(categoryIndex)
                                }}
                                className='text-red-400 text-xs underline underline-offset-4'
                            >
                                Remove category
                            </button>
                        </div>
                        <label title={'Name'}>
                            <div className='mb-1'>Category name</div>
                            <input
                                className='border-2 border-gray-600  rounded-lg px-2 py-1 bg-transparent'
                                placeholder='Enter category name...'
                                {...register(`categories.${categoryIndex}.name`)}
                            />
                            <div className='text-red-600'>
                                {
                                    /* Error: Category name */
                                    errors?.categories?.[categoryIndex]?.name?.message
                                }
                            </div>
                        </label>
                        <ManageExpenseLines categoryIndex={categoryIndex} />
                        <div className='text-red-600 text-sm mt-1'>
                            {
                                /* Error: Category expenseLines */
                                errors?.categories?.[categoryIndex]?.expenseLines?.message
                            }
                        </div>
                    </div>
                )
            })}
            <button
                type='button'
                onClick={() => {
                    append({ expenseLines: [{ name: '', amount: 0 }], name: '' })
                }}
                className='text-gray-600 text-center w-full underline underline-offset-4 py-2'
            >
                add category
            </button>
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
