<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Entity\Income;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetIncomeController extends AbstractController
{
    #[Route('/incomes/{id}', name: 'app_incomes_get')]
    public function get(Income $income): Response
    {
        return $this->render('incomes/get.html.twig', [
            'income' => $income,
        ]);
    }
}
