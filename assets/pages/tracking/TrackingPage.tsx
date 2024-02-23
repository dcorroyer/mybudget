import React, { useState } from 'react'
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'
import { Input } from '@/components/ui/input'
import { EuroIcon } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { useForm } from 'react-hook-form'
import { loginFormSchema, loginFormType } from '@/schemas'
import { zodResolver } from '@hookform/resolvers/zod'
import { InputSuffixIn } from '@/components/ui/input-suffix-in'

function TrackingPage(): React.JSX.Element {

    const [expenses, setExpenses] = useState([{ name: '', amount: '' }]);

    const handleAddExpense = () => {
        setExpenses([...expenses, { name: '', amount: '' }]);
    };

    const handleExpenseChange = (index: number, fieldName: string, value: string) => {
        const updatedExpenses = [...expenses];
        updatedExpenses[index][fieldName] = value;
        setExpenses(updatedExpenses);
    };

    const loginForm = useForm<loginFormType>({
        resolver: zodResolver(loginFormSchema),
        defaultValues: {
            email: '',
            password: '',
        },
    })

    function onSubmit(values: loginFormType): void {
        console.log(values)
    }

    return (
        <div className="flex flex-col items-center py-12 sm:px-6 lg:px-8">
            <Form {...loginForm}>
                <form onSubmit={loginForm.handleSubmit(onSubmit)} className="space-y-2">
                    <Card className="w-full max-w-md">
                        <CardHeader>
                            <CardTitle>Insurances</CardTitle>
                        </CardHeader>
                        <CardContent className="flex flex-col space-y-4">
                            {expenses.map((expense, index) => (
                                <div key={index} className="flex space-x-1">
                                    <FormField
                                        control={loginForm.control}
                                        name={`email_${index}`}
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormLabel>Name</FormLabel>
                                                <FormControl>
                                                    <Input
                                                        placeholder="Name"
                                                        value={expense.name}
                                                        onChange={(e) => handleExpenseChange(index, 'name', e.target.value)}
                                                    />
                                                </FormControl>
                                                <FormMessage />
                                            </FormItem>
                                        )}
                                    />
                                    <FormField
                                        control={loginForm.control}
                                        name={`password_${index}`}
                                        render={({ field }) => (
                                            <FormItem>
                                                <FormLabel>Amount</FormLabel>
                                                <FormControl>
                                                    <InputSuffixIn
                                                        placeholder="Amount"
                                                        value={expense.amount}
                                                        onChange={(e) => handleExpenseChange(index, 'amount', e.target.value)}
                                                        suffix={<EuroIcon />}
                                                    />
                                                </FormControl>
                                                <FormMessage />
                                            </FormItem>
                                        )}
                                    />
                                </div>
                            ))}
                        </CardContent>
                        <CardFooter>
                            <Button variant="ghost" onClick={handleAddExpense}>
                                Add an expense
                            </Button>
                        </CardFooter>
                    </Card>
                </form>
            </Form>
        </div>
    );
}

export default TrackingPage
