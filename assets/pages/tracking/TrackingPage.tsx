import React from 'react'
import {
    FormTypeCreateBudget,
    useFormContextCreateBudget,
    useFormCreateBudget,
} from '@/schemas/budget'
import { useFieldArray } from 'react-hook-form'
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { Card, CardContent, CardHeader } from '@/components/ui/card'
import { DeleteIcon, Euro, EuroIcon, Trash2Icon, XIcon } from 'lucide-react'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { InputSuffixIn } from '@/components/ui/input-suffix-in'

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
        <div className="flex flex-col items-center py-12 sm:px-6 lg:px-8 max-w-screen-lg mx-auto">
            <Form {...form}>
                <form onSubmit={handleSubmit(onSubmit)} className="space-y-8">
                    <div className="space-y-2 py-6 px-4 sm:px-0">
                        {fields.map((category, categoryIndex) => {
                            return (
                                <div key={category.id}>
                                    <Card className="space-y-4">
                                        <CardHeader>
                                            <div className="flex items-center relative">
                                                <FormField
                                                    control={form.control}
                                                    name={`categories.${categoryIndex}.name`}
                                                    render={({ field }) => (
                                                        <FormItem>
                                                            <FormControl>
                                                                <Input
                                                                    placeholder="Enter category name..."
                                                                    {...field}
                                                                />
                                                            </FormControl>
                                                            <FormMessage
                                                                content={
                                                                    errors?.categories?.[
                                                                        categoryIndex
                                                                        ]?.name?.message
                                                                }
                                                            />
                                                        </FormItem>
                                                    )}
                                                />
                                                <DeleteIcon
                                                    onClick={() => {
                                                        remove(categoryIndex)
                                                    }}
                                                    className="cursor-pointer hover:text-red-400 absolute right-0"
                                                />
                                            </div>
                                        </CardHeader>
                                        <CardContent>
                                            <ManageExpenseLines categoryIndex={categoryIndex} />
                                            <div className="text-red-600 text-sm mt-1">
                                                {
                                                    /* Error: Category expenseLines */
                                                    errors?.categories?.[categoryIndex]
                                                        ?.expenseLines?.message
                                                }
                                            </div>
                                        </CardContent>
                                    </Card>
                                </div>
                            )
                        })}
                        <Button
                            onClick={() => {
                                append({ expenseLines: [{ name: '', amount: 0 }], name: '' })
                            }}
                            variant="ghost"
                            className="text-center w-full underline underline-offset-4 py-2"
                        >
                            add category
                        </Button>
                        <Button className="px-4 py-2 rounded-lg" type="submit" variant="ghost">
                            Submit
                        </Button>
                    </div>
                </form>
            </Form>
        </div>
    )
}

const ManageExpenseLines = ({ categoryIndex }: { categoryIndex: number }) => {
    const {
        formState: { errors },
        watch,
        control,
    } = useFormContextCreateBudget()

    const form = useFormCreateBudget()

    const formData = watch()
    console.log('formData, errors', formData, errors)

    const { append, remove, fields } = useFieldArray({
        name: `categories.${categoryIndex}.expenseLines`,
        control,
    })

    return (
        <div className="space-y-4">
            {fields.map((expenseLine, expenseLineIndex) => {
                return (
                    <div key={expenseLine.id} className="flex items-center space-x-2 relative">
                        <FormField
                            control={form.control}
                            name={`categories.${categoryIndex}.expenseLines.${expenseLineIndex}.name`}
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Name</FormLabel>
                                    <FormControl>
                                        <Input placeholder="Name" {...field} />
                                    </FormControl>
                                    <FormMessage
                                        content={
                                            errors?.categories?.[categoryIndex]?.expenseLines?.[
                                                expenseLineIndex
                                                ]?.name?.message
                                        }
                                    />
                                </FormItem>
                            )}
                        />
                        <FormField
                            control={form.control}
                            name={`categories.${categoryIndex}.expenseLines.${expenseLineIndex}.amount`}
                            render={({ field }) => (
                                <FormItem>
                                    <FormLabel>Amount</FormLabel>
                                    <FormControl>
                                        <InputSuffixIn
                                            placeholder="Amount"
                                            {...field}
                                            suffix={<EuroIcon />}
                                        />
                                    </FormControl>
                                    <FormMessage
                                        content={
                                            errors?.categories?.[categoryIndex]?.expenseLines?.[
                                                expenseLineIndex
                                                ]?.amount?.message
                                        }
                                    />
                                </FormItem>
                            )}
                        />
                        <XIcon
                            onClick={() => {
                                remove(expenseLineIndex)
                            }}
                            className="cursor-pointer hover:text-red-400 absolute top-2 -translate-y-1 right-0"
                        />
                    </div>
                )
            })}
            <Button
                variant="ghost"
                onClick={() => {
                    append({ name: '', amount: 0 })
                }}
                className="text-gray-600 text-center w-full underline underline-offset-4 py-2"
            >
                add expense
            </Button>
        </div>
    )
}
