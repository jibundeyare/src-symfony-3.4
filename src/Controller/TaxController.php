<?php
// src/Controller/TaxController.php

namespace App\Controller;

use App\Service\TaxCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;

/**
 * @Route("/tax")
 */
class TaxController extends AbstractController
{
    /**
     * @Route("/calculator", name="tax_calculator")
     */
    public function index(Request $request)
    {
        $errors = [];
        $rates = TaxCalculator::RATES;
        $price = 0;
        $rate = TaxCalculator::RATES['normal'];
        $tip = 0;

        if ($request->getMethod() == 'POST') {
            $price = $request->request->get('price');
            $rate = $request->request->get('rate');

            if (empty($price)) {
                $errors['price'] = 'Veuillez renseigner ce champ';
            }

            if (!$errors) {
                $tc = new TaxCalculator();
                $tip = $tc->getTaxIncludedPrice($price);
            }
        }

        return $this->render('tax/calculator.html.twig', [
            'errors' => $errors,
            'rates' => $rates,
            'price' => $price,
            'rate' => $rate,
            'tip' => $tip,
        ]);
    }
}
