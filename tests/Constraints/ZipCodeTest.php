<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use ZipCodeValidator\Constraints\ZipCode;

class ZipCodeTest extends TestCase
{
    public function testMissingOptionsExceptionWhenIsoAndGetterIsEmpty(): void
    {
        $this->expectException(MissingOptionsException::class);
        $constraint = new ZipCode(null);
    }
}