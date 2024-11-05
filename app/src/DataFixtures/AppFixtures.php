<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\AccountTypesEnum;
use App\Enum\TransactionTypesEnum;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\BalanceHistoryFactory;
use App\Tests\Common\Factory\TransactionFactory;
use App\Tests\Common\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordEncoder
    ) {
    }

    #[\Override]
    public function load(ObjectManager $manager): void
    {
        // Création du user
        $user = new User();
        $hashedPassword = $this->passwordEncoder->hashPassword($user, 'password');

        $user = UserFactory::new([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@admin.local',
            'password' => $hashedPassword,
        ])->create();

        // Création des comptes
        $accounts = [
            'Livret A' => AccountFactory::new([
                'name' => 'Livret A',
                'type' => AccountTypesEnum::SAVINGS,
                'user' => $user,
            ])->create(),
            'Livret B' => AccountFactory::new([
                'name' => 'Livret B',
                'type' => AccountTypesEnum::SAVINGS,
                'user' => $user,
            ])->create(),
            'Livret C' => AccountFactory::new([
                'name' => 'Livret C',
                'type' => AccountTypesEnum::SAVINGS,
                'user' => $user,
            ])->create(),
        ];

        // Pour chaque compte, création des transactions et historiques de balance
        foreach ($accounts as $account) {
            $balance = 0.0;
            $baseDate = new \DateTime('2024-01-01');

            // Création des transactions
            $transactions = [
                // Janvier 2024
                [
                    'description' => 'Dépôt initial',
                    'amount' => 1000.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+1 day')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                [
                    'description' => 'Intérêts',
                    'amount' => 50.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+15 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Février 2024
                [
                    'description' => 'Virement entrant mensuel',
                    'amount' => 500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+32 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                [
                    'description' => 'Retrait pour vacances',
                    'amount' => 300.00,
                    'type' => TransactionTypesEnum::DEBIT,
                    'date' => (clone $baseDate)->modify('+45 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Mars 2024
                [
                    'description' => 'Prime annuelle',
                    'amount' => 2000.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+60 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                [
                    'description' => 'Intérêts trimestriels',
                    'amount' => 75.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+90 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Avril 2024
                [
                    'description' => 'Virement entrant mensuel',
                    'amount' => 500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+92 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Mai 2024
                [
                    'description' => 'Retrait vacances été',
                    'amount' => 1500.00,
                    'type' => TransactionTypesEnum::DEBIT,
                    'date' => (clone $baseDate)->modify('+120 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Juin 2024
                [
                    'description' => 'Intérêts trimestriels',
                    'amount' => 80.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+180 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Juillet 2024
                [
                    'description' => 'Prime vacances',
                    'amount' => 800.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+210 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Août 2024
                [
                    'description' => 'Virement entrant exceptionnel',
                    'amount' => 3000.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+240 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Septembre 2024
                [
                    'description' => 'Intérêts trimestriels',
                    'amount' => 95.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+270 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Octobre 2024
                [
                    'description' => 'Retrait imprévu',
                    'amount' => 250.00,
                    'type' => TransactionTypesEnum::DEBIT,
                    'date' => (clone $baseDate)->modify('+300 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Novembre 2024
                [
                    'description' => 'Virement entrant mensuel',
                    'amount' => 500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+330 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Décembre 2024
                [
                    'description' => 'Prime de fin d\'année',
                    'amount' => 1500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+360 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Janvier 2025
                [
                    'description' => 'Intérêts annuels',
                    'amount' => 450.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+365 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Mars 2025
                [
                    'description' => 'Retrait pour projet',
                    'amount' => 5000.00,
                    'type' => TransactionTypesEnum::DEBIT,
                    'date' => (clone $baseDate)->modify('+425 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
                // Juin 2025
                [
                    'description' => 'Remboursement prêt',
                    'amount' => 2500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('+545 days')->setTime(random_int(0, 23), random_int(0, 59), random_int(0, 59)),
                ],
            ];

            foreach ($transactions as $transactionData) {
                $transaction = TransactionFactory::new([
                    'account' => $account,
                    'description' => $transactionData['description'],
                    'amount' => $transactionData['amount'],
                    'type' => $transactionData['type'],
                    'date' => $transactionData['date'],
                ])->create();

                // Mise à jour du solde
                $balanceBeforeTransaction = $balance;
                $balance += $transactionData['type'] === TransactionTypesEnum::CREDIT 
                    ? $transactionData['amount'] 
                    : -$transactionData['amount'];
                $balanceAfterTransaction = $balance;

                // Création de l'historique de balance
                BalanceHistoryFactory::new([
                    'account' => $account,
                    'transaction' => $transaction,
                    'balanceBeforeTransaction' => $balanceBeforeTransaction,
                    'balanceAfterTransaction' => $balanceAfterTransaction,
                    'date' => $transactionData['date'],
                ])->create();
            }
        }
    }
}
