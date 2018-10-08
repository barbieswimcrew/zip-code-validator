<?php

use Symfony\Component\Validator\Exception\MissingOptionsException;
use ZipCodeValidator\Constraints\ZipCode;

class ZipCodeTest extends \PHPUnit\Framework\TestCase
{
    public function testMissingOptionsExceptionWhenIsoAndGetterIsEmpty()
    {
        $this->expectException(MissingOptionsException::class);
        $constraint = new ZipCode(null);
    }
}