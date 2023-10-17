<?php

declare(strict_types=1);

namespace App\Controller\Charge;

use App\Repository\ChargeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListChargeController extends AbstractController
{
    #[Route('/charges', name: 'app_charges_list')]
    public function index(ChargeRepository $chargeRepository): Response
    {
        return $this->render('charges/index.html.twig', [
            'charges' => $chargeRepository->findAll(),
        ]);
    }
}
