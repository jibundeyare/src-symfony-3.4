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

    public function testUknownRate()
    {
        $this->expectException(\Exception::class);

        $price = 10;
        $rateType = 42;

        $tc = new TaxCalculator();
        $tip = $tc->getTaxIncludedPrice($price, $rateType);
    }
}
