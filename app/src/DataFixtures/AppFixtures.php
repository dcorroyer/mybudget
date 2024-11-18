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
use Carbon\Carbon;
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
            $baseDate = Carbon::now();

            // Création des transactions
            $transactions = [
                // Mois -18 (Mai 2023)
                [
                    'description' => 'Placement de 500 euros - Virement mensuel',
                    'amount' => 500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-18 months'),
                ],
                // Mois -17 (Juin 2023)
                [
                    'description' => 'Placement de 75 euros - Intérêts trimestriels',
                    'amount' => 75.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-17 months'),
                ],
                // Mois -16 (Juillet 2023)
                [
                    'description' => 'Placement de 1000 euros - Dépôt initial',
                    'amount' => 1000.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-16 months'),
                ],
                // Mois -15 (Août 2023)
                [
                    'description' => 'Placement de 800 euros - Prime vacances',
                    'amount' => 800.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-15 months'),
                ],
                // Mois -14 (Septembre 2023)
                [
                    'description' => 'Placement de 95 euros - Intérêts trimestriels',
                    'amount' => 95.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-14 months'),
                ],
                // Mois -13 (Octobre 2023)
                [
                    'description' => 'Placement de 500 euros - Virement mensuel',
                    'amount' => 500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-13 months'),
                ],
                // Mois -12 (Novembre 2023)
                [
                    'description' => 'Achat de 800 euros - Électroménager',
                    'amount' => 800.00,
                    'type' => TransactionTypesEnum::DEBIT,
                    'date' => (clone $baseDate)->modify('-12 months'),
                ],
                // Mois -11 (Décembre 2023)
                [
                    'description' => 'Placement de 1500 euros - Prime fin d\'année',
                    'amount' => 1500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-11 months'),
                ],
                // Mois -10 (Janvier 2024)
                [
                    'description' => 'Placement de 450 euros - Intérêts annuels',
                    'amount' => 450.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-10 months'),
                ],
                // Mois -9 (Février 2024)
                [
                    'description' => 'Placement de 500 euros - Virement mensuel',
                    'amount' => 500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-9 months'),
                ],
                // Mois -8 (Mars 2024)
                [
                    'description' => 'Placement de 125 euros - Intérêts trimestriels',
                    'amount' => 125.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-8 months'),
                ],
                // Mois -7 (Avril 2024)
                [
                    'description' => 'Placement de 2000 euros - Prime exceptionnelle',
                    'amount' => 2000.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-7 months'),
                ],
                // Mois -6 (Mai 2024)
                [
                    'description' => 'Achat de 1200 euros - Mobilier',
                    'amount' => 1200.00,
                    'type' => TransactionTypesEnum::DEBIT,
                    'date' => (clone $baseDate)->modify('-6 months'),
                ],
                // Mois -5 (Juin 2024)
                [
                    'description' => 'Placement de 130 euros - Intérêts trimestriels',
                    'amount' => 130.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-5 months'),
                ],
                // Mois -4 (Juillet 2024)
                [
                    'description' => 'Achat de 1500 euros - Vacances été',
                    'amount' => 1500.00,
                    'type' => TransactionTypesEnum::DEBIT,
                    'date' => (clone $baseDate)->modify('-4 months'),
                ],
                // Mois -3 (Août 2024)
                [
                    'description' => 'Placement de 900 euros - Prime vacances',
                    'amount' => 900.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-3 months'),
                ],
                // Mois -2 (Septembre 2024)
                [
                    'description' => 'Placement de 140 euros - Intérêts trimestriels',
                    'amount' => 140.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-2 months'),
                ],
                // Mois -1 (Octobre 2024)
                [
                    'description' => 'Placement de 500 euros - Virement mensuel',
                    'amount' => 500.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-1 month'),
                ],
                // Mois en cours (Novembre 2024)
                [
                    'description' => 'Placement de 350 euros - Remboursement assurance',
                    'amount' => 350.00,
                    'type' => TransactionTypesEnum::CREDIT,
                    'date' => (clone $baseDate)->modify('-5 days'),
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
