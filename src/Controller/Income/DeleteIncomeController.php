<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Entity\Income;
use App\Service\Income\IncomeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteIncomeController extends AbstractController
{
    #[Route('/incomes/{id}/delete', name: 'app_incomes_delete')]
    public function delete(Request $request, Income $income, IncomeService $incomeService): Response
    {
        if ($this->isCsrfTokenValid('delete' . $income->getId(), (string) $request->request->get('_token'))) {
            $incomeService->delete($income);
        }

        return $this->redirectToRoute('app_incomes_list');
    }
}
