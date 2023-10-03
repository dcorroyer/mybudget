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

class CreateIncomeController extends AbstractController
{
    #[Route('/incomes/create', name: 'app_income_create')]
    public function create(Request $request, IncomeService $incomeService): Response
    {
        $income = new Income();
        $form = $this->createForm(IncomeFormType::class, $income);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $incomeService->create($income);

            return $this->redirectToRoute('app_incomes');
        }

        return $this->render('incomes/create.html.twig', [
            'createIncomeForm' => $form->createView(),
        ]);
    }
}
