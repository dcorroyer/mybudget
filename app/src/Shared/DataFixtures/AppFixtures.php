<?php

declare(strict_types=1);

namespace App\Shared\DataFixtures;

use App\Savings\Enum\AccountTypesEnum;
use App\Savings\Enum\TransactionTypesEnum;
use App\Shared\Entity\User;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\BudgetFactory;
use App\Tests\Common\Factory\ExpenseFactory;
use App\Tests\Common\Factory\IncomeFactory;
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

        // Création des budgets prévisionnels
        $baseDate = Carbon::now();

        for ($i = -5; $i <= 3; ++$i) {
            $date = (clone $baseDate)->startOfMonth()->addMonths($i);

            BudgetFactory::new([
                'user' => $user,
                'date' => $date,
                'incomes' => [
                    IncomeFactory::new([
                        'name' => 'Salaire',
                        'amount' => 2500.00,
                    ]),
                    IncomeFactory::new([
                        'name' => 'Autres revenus',
                        'amount' => 500.00,
                    ]),
                ],
                'expenses' => [
                    ExpenseFactory::new([
                        'name' => 'Loyer',
                        'category' => 'Habitation',
                        'amount' => 800.00,
                    ]),
                    ExpenseFactory::new([
                        'name' => 'Électricité',
                        'category' => 'Habitation',
                        'amount' => 150.00,
                    ]),
                    ExpenseFactory::new([
                        'name' => 'Assurance habitation',
                        'category' => 'Abonnements',
                        'amount' => 150.00,
                    ]),
                    ExpenseFactory::new([
                        'name' => 'Assurance voiture',
                        'category' => 'Abonnements',
                        'amount' => 100.00,
                    ]),
                ],
            ])->create();
        }

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

            // Création des transactions avec plusieurs transactions par mois pour tester l'agrégation
            $transactions = [
                // Janvier 2024 - 5 transactions (test agrégation multiple)
                [
                    'description' => 'Dépôt initial - Solde de départ',
                    'amount' => 1000.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-18 months')->startOfMonth()->addDays(2),
                ],
                [
                    'description' => 'Virement salaire - Part épargne',
                    'amount' => 300.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-18 months')->startOfMonth()->addDays(5),
                ],
                [
                    'description' => 'Retrait urgence - Frais médicaux',
                    'amount' => 150.00,
                    'type' => TransactionTypesEnum::WITHDRAWAL,
                    'date' => (clone $baseDate)->modify('-18 months')->startOfMonth()->addDays(15),
                ],
                [
                    'description' => 'Remboursement assurance',
                    'amount' => 80.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-18 months')->startOfMonth()->addDays(20),
                ],
                [
                    'description' => 'Virement complémentaire',
                    'amount' => 120.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-18 months')->startOfMonth()->addDays(28),
                ],
                // Solde fin janvier: 0 + 1000 + 300 - 150 + 80 + 120 = 1350€

                // Février 2024 - 3 transactions
                [
                    'description' => 'Virement mensuel épargne',
                    'amount' => 400.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-17 months')->startOfMonth()->addDays(1),
                ],
                [
                    'description' => 'Achat électroménager',
                    'amount' => 250.00,
                    'type' => TransactionTypesEnum::WITHDRAWAL,
                    'date' => (clone $baseDate)->modify('-17 months')->startOfMonth()->addDays(12),
                ],
                [
                    'description' => 'Intérêts mensuels',
                    'amount' => 15.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-17 months')->endOfMonth(),
                ],
                // Solde fin février: 1350 + 400 - 250 + 15 = 1515€

                // Mars 2024 - 1 seule transaction (référence)
                [
                    'description' => 'Prime trimestrielle',
                    'amount' => 500.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-16 months')->startOfMonth()->addDays(15),
                ],
                // Solde fin mars: 1515 + 500 = 2015€

                // Avril 2024 - 8 transactions (mois chargé)
                [
                    'description' => 'Virement salaire épargne',
                    'amount' => 350.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-15 months')->startOfMonth()->addDays(1),
                ],
                [
                    'description' => 'Achat mobilier - Acompte',
                    'amount' => 200.00,
                    'type' => TransactionTypesEnum::WITHDRAWAL,
                    'date' => (clone $baseDate)->modify('-15 months')->startOfMonth()->addDays(3),
                ],
                [
                    'description' => 'Remboursement crédit',
                    'amount' => 180.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-15 months')->startOfMonth()->addDays(7),
                ],
                [
                    'description' => 'Achat mobilier - Solde',
                    'amount' => 800.00,
                    'type' => TransactionTypesEnum::WITHDRAWAL,
                    'date' => (clone $baseDate)->modify('-15 months')->startOfMonth()->addDays(10),
                ],
                [
                    'description' => 'Virement exceptionnel',
                    'amount' => 600.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-15 months')->startOfMonth()->addDays(14),
                ],
                [
                    'description' => 'Retrait frais divers',
                    'amount' => 50.00,
                    'type' => TransactionTypesEnum::WITHDRAWAL,
                    'date' => (clone $baseDate)->modify('-15 months')->startOfMonth()->addDays(18),
                ],
                [
                    'description' => 'Complément épargne',
                    'amount' => 120.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-15 months')->startOfMonth()->addDays(25),
                ],
                [
                    'description' => 'Intérêts trimestriels',
                    'amount' => 45.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-15 months')->endOfMonth(),
                ],
                // Solde fin avril: 2015 + 350 - 200 + 180 - 800 + 600 - 50 + 120 + 45 = 2260€

                // Mai à Octobre 2024 - 1-2 transactions par mois
                [
                    'description' => 'Virement mensuel',
                    'amount' => 300.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-14 months')->startOfMonth()->addDays(5),
                ],
                [
                    'description' => 'Achat vacances',
                    'amount' => 400.00,
                    'type' => TransactionTypesEnum::WITHDRAWAL,
                    'date' => (clone $baseDate)->modify('-13 months')->startOfMonth()->addDays(10),
                ],
                [
                    'description' => 'Prime été',
                    'amount' => 800.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-12 months')->startOfMonth()->addDays(15),
                ],
                [
                    'description' => 'Virement mensuel',
                    'amount' => 300.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-11 months')->startOfMonth()->addDays(5),
                ],
                [
                    'description' => 'Dépenses rentrée',
                    'amount' => 250.00,
                    'type' => TransactionTypesEnum::WITHDRAWAL,
                    'date' => (clone $baseDate)->modify('-10 months')->startOfMonth()->addDays(8),
                ],
                [
                    'description' => 'Virement mensuel',
                    'amount' => 300.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-9 months')->startOfMonth()->addDays(5),
                ],

                // Novembre 2024 - 4 transactions (mois récent)
                [
                    'description' => 'Virement mensuel',
                    'amount' => 300.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-8 months')->startOfMonth()->addDays(2),
                ],
                [
                    'description' => 'Achat électronique',
                    'amount' => 180.00,
                    'type' => TransactionTypesEnum::WITHDRAWAL,
                    'date' => (clone $baseDate)->modify('-8 months')->startOfMonth()->addDays(12),
                ],
                [
                    'description' => 'Remboursement impôts',
                    'amount' => 220.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-8 months')->startOfMonth()->addDays(20),
                ],
                [
                    'description' => 'Intérêts fin année',
                    'amount' => 35.00,
                    'type' => TransactionTypesEnum::DEPOSIT,
                    'date' => (clone $baseDate)->modify('-8 months')->endOfMonth(),
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

                // Mise à jour du solde (plus nécessaire pour BalanceHistory, mais gardé pour info)
                $balance += $transactionData['type'] === TransactionTypesEnum::DEPOSIT
                    ? $transactionData['amount']
                    : -$transactionData['amount'];
            }
        }
    }
}
