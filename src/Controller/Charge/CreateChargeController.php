<?php

declare(strict_types=1);

namespace App\Controller\Charge;

use App\Entity\Charge;
use App\Form\Charge\ChargeFormType;
use App\Service\Charge\ChargeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateChargeController extends AbstractController
{
    #[Route('/charges/create', name: 'app_charges_create')]
    public function create(Request $request, ChargeService $chargeService): Response
    {
        $charge = new Charge();
        $form = $this->createForm(ChargeFormType::class, $charge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chargeService->create($charge);

            return $this->redirectToRoute('app_charges_list');
        }

        return $this->render('charges/create.html.twig', [
            'chargeForm' => $form->createView(),
        ]);
    }
}
