import { Card, SimpleGrid, Text } from '@mantine/core'
import React from 'react'
import { Transaction } from '../types/transactions'

interface TransactionListProps {
  transactions: Transaction[]
}

export const TransactionList: React.FC<TransactionListProps> = ({ transactions }) => {
  return (
    <Card radius='lg' py='xl' mt='xl'>
      <Card.Section inheritPadding px='xl' pb='xs'>
        <Text fw={500} mb='md'>Transactions History</Text>
        {transactions.map((transaction) => (
          <SimpleGrid 
            key={transaction.id} 
            cols={3} 
            mb='sm'
            style={{ alignItems: 'center' }}
          >
            <Text>{transaction.description}</Text>
            <Text>{new Date(transaction.date).toLocaleDateString()}</Text>
            <Text 
              fw={500} 
              c={transaction.type === 'CREDIT' ? 'green' : 'red'}
              style={{ textAlign: 'right' }}
            >
              {transaction.type === 'CREDIT' ? '+' : '-'}{transaction.amount} €
            </Text>
          </SimpleGrid>
        ))}
      </Card.Section>
    </Card>
  )
} 