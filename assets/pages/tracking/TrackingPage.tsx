import React, { useState } from 'react'
import { Input } from '@/components/ui/input'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'

interface Element {
    name: string
    amount: string
}

interface Category {
    categoryName: string
    elements: Element[]
}

const TrackingPage: React.FC = () => {
    const [categories, setCategories] = useState<Category[]>([])

    const updateCategories = (updatedCategories: Category[]) => {
        setCategories(updatedCategories)
    }

    const addCategory = () => {
        updateCategories([
            ...categories,
            { categoryName: '', elements: [{ name: '', amount: '' }] },
        ])
    }

    const addElement = (categoryIndex: number) => {
        const updatedCategories = [...categories]
        updatedCategories[categoryIndex].elements.push({ name: '', amount: '' })
        updateCategories(updatedCategories)
    }

    const handleChange = (
        categoryIndex: number,
        elementIndex: number,
        field: keyof Element,
        value: string,
    ) => {
        const updatedCategories = [...categories]
        updatedCategories[categoryIndex].elements[elementIndex][field] = value
        updateCategories(updatedCategories)
    }

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault()
        console.log(categories)
    }

    return (
        <div className='flex flex-col items-center py-12 sm:px-6 lg:px-8'>
            <form onSubmit={handleSubmit}>
                {categories.map((category, categoryIndex) => (
                    <div key={categoryIndex}>
                        <Input
                            value={category.categoryName}
                            placeholder='Category Name'
                            onChange={(e) => handleChange(categoryIndex, 0, 'name', e.target.value)}
                        />
                        {category.elements.map((element, elementIndex) => (
                            <Card key={elementIndex}>
                                <Input
                                    value={element.name}
                                    placeholder='Name'
                                    onChange={(e) =>
                                        handleChange(
                                            categoryIndex,
                                            elementIndex,
                                            'name',
                                            e.target.value,
                                        )
                                    }
                                />
                                <Input
                                    value={element.amount}
                                    placeholder='Amount'
                                    onChange={(e) =>
                                        handleChange(
                                            categoryIndex,
                                            elementIndex,
                                            'amount',
                                            e.target.value,
                                        )
                                    }
                                />
                            </Card>
                        ))}
                        <Button onClick={() => addElement(categoryIndex)}>Add Element</Button>
                    </div>
                ))}
                <Button onClick={addCategory}>Add Category</Button>
                <Button type='submit'>Submit</Button>
            </form>
        </div>
    )
}

export default TrackingPage
