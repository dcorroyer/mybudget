<?php

declare(strict_types=1);

namespace App\Controller\Expense;

use App\Entity\Expense;
use App\Form\Expense\ExpenseFormType;
use App\Service\Expense\ExpenseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateExpenseController extends AbstractController
{
    #[Route('/expenses/create', name: 'app_expenses_create')]
    public function create(Request $request, ExpenseService $expenseService): Response
    {
        $expense = new Expense();
        $form = $this->createForm(ExpenseFormType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseService->create($expense);

            return $this->redirectToRoute('app_expenses_list');
        }

        return $this->render('expenses/create.html.twig', [
            'expenseForm' => $form->createView(),
        ]);
    }
}
