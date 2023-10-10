<?php

declare(strict_types=1);

namespace App\Controller\Income;

use App\Repository\IncomeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListIncomeController extends AbstractController
{
    #[Route('/incomes', name: 'app_incomes_list')]
    public function index(IncomeRepository $incomeRepository): Response
    {
        return $this->render('incomes/index.html.twig', [
            'incomes' => $incomeRepository->findAll(),
        ]);
    }
}
