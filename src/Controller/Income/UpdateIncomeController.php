<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Entity\Income;
use App\Form\Income\IncomeFormType;
use App\Service\Income\IncomeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateIncomeController extends AbstractController
{
    #[Route('/incomes/{id}/edit', name: 'app_incomes_edit')]
    public function update(Request $request, Income $income, IncomeService $incomeService): Response
    {
        $form = $this->createForm(IncomeFormType::class, $income);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incomeService->update($income);

            return $this->redirectToRoute('app_incomes_list');
        }

        return $this->render('incomes/update.html.twig', [
            'incomeForm' => $form->createView(),
        ]);
    }
}
