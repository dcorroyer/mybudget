<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\Repository\ExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListExpenseController extends AbstractController
{
    #[Route('/expenses', name: 'app_expenses_list')]
    public function index(ExpenseRepository $expenseRepository): Response
    {
        return $this->render('expenses/index.html.twig', [
            'expenses' => $expenseRepository->findAll(),
        ]);
    }
}
