<?php

namespace Tests\Unit\Trais;

use App\Traits\FormatMoney;
use PHPUnit\Framework\TestCase;

class FormatMoneyTest extends TestCase
{
    /**
     * @return void
     */
    public function test_convert_cents_to_real_method(): void
    {
        $trait = $this->getObjectForTrait(FormatMoney::class);

        $this->assertEquals('1,00', $trait->convertCentsToReal(100));
        $this->assertEquals('10,00', $trait->convertCentsToReal(1000));
        $this->assertEquals('100,00', $trait->convertCentsToReal(10000));
        $this->assertEquals('1,72', $trait->convertCentsToReal(172));
        $this->assertEquals('10,31', $trait->convertCentsToReal(1031));
        $this->assertEquals('100,25', $trait->convertCentsToReal(10025));
        $this->assertEquals('1.000,00', $trait->convertCentsToReal(100000));
        $this->assertEquals('10.000,00', $trait->convertCentsToReal(1000000));
        $this->assertEquals('15.456,21', $trait->convertCentsToReal(1545621));
    }
}
