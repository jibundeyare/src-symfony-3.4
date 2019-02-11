<?php

namespace App\Tests;

use App\Service\TaxCalculator;
use PHPUnit\Framework\TestCase;

class TaxCalculatorTest extends TestCase
{
    public function testNoRatePrice()
    {
        $price = 10;
        $taxRate = 0.2;
        $taxIncludedPrice = $price + $price * $taxRate;

        $tc = new TaxCalculator();
        $tip = $tc->getTaxIncludedPrice($price);
        $this->assertEquals($taxIncludedPrice, $tip);
    }

    public function testNormalRatePrice()
    {
        $price = 10;
        $taxRate = 0.2;
        $taxIncludedPrice = $price + $price * $taxRate;

        $tc = new TaxCalculator();
        $tip = $tc->getTaxIncludedPrice($price, TaxCalculator::RATES['normal']);

        $this->assertEquals($taxIncludedPrice, $tip);
    }

    public function testReducedRate1Price()
    {
        $price = 10;
        $taxRate = 0.1;
        $taxIncludedPrice = $price + $price * $taxRate;

        $tc = new TaxCalculator();
        $tip = $tc->getTaxIncludedPrice($price, TaxCalculator::RATES['taux réduit 1']);

        $this->assertEquals($taxIncludedPrice, $tip);
    }

    public function testReducedRate2Price()
    {
        $price = 10;
        $taxRate = 0.055;
        $taxIncludedPrice = $price + $price * $taxRate;

        $tc = new TaxCalculator();
        $tip = $tc->getTaxIncludedPrice($price, TaxCalculator::RATES['taux réduit 2']);

        $this->assertEquals($taxIncludedPrice, $tip);
    }

    public function testReducedRate3Price()
    {
        $price = 10;
        $taxRate = 0.021;
        $taxIncludedPrice = $price + $price * $taxRate;

        $tc = new TaxCalculator();
        $tip = $tc->getTaxIncludedPrice($price, TaxCalculator::RATES['taux réduit 3']);

        $this->assertEquals($taxIncludedPrice, $tip);
    }

    public function testUknownRate()
    {
        $this->expectException(\Exception::class);

        $price = 10;
        $rateType = 42;

        $tc = new TaxCalculator();
        $tip = $tc->getTaxIncludedPrice($price, $rateType);
    }
}
