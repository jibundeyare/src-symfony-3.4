<?php
// src/Service/TaxCalculator

namespace App\Service;

/**
 * TaxCalculator
 */
class TaxCalculator
{
    const RATES = [
        'normal' => 1,
        'taux réduit 1' => 2,
        'taux réduit 2' => 3,
        'taux réduit 3' => 4,
    ];

    /**
     * tax rates are :
     * - 'normal': 20.0 %
     * - 'taux réduit 1': 10.0 %
     * - 'taux réduit 2': 5.5 %
     * - 'taux réduit 3': 2.1 %
     *
     * source : [CEDEF - Quels sont les taux de TVA en vigueur en France et dans l'Union européenne ? | Le portail des ministères économiques et financiers](https://www.economie.gouv.fr/cedef/taux-tva-france-et-union-europeenne)
     */
    public function getTaxIncludedPrice(?float $price, int $taxRate = self::RATES['normal']): float
    {
        $taxIncludedPrice = 0;

        switch ($taxRate) {
            case self::RATES['normal']:
                $taxIncludedPrice = $price + $price * 0.2;
                break;
            case self::RATES['taux réduit 1']:
                $taxIncludedPrice = $price + $price * 0.1;
                break;
            case self::RATES['taux réduit 2']:
                $taxIncludedPrice = $price + $price * 0.055;
                break;
            case self::RATES['taux réduit 3']:
                $taxIncludedPrice = $price + $price * 0.021;
                break;
            default:
                throw new \Exception("Taux de TVA $taxRate inconnu");
        }
    
        return $taxIncludedPrice;
    }
}
