<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use ZipCodeValidator\Constraints\ZipCode;

class ZipCodeTest extends TestCase
{
    public function testMissingOptionsExceptionWhenIsoAndGetterIsEmpty(): void
    {
        $this->expectException(MissingOptionsException::class);
        $constraint = new ZipCode(null);
    }

    public function testLegacyStringOptionSetsIso(): void
    {
        $constraint = new ZipCode('DE');

        $this->assertSame('DE', $constraint->iso);
    }

    public function testLegacyArrayOptionsAreStillSupported(): void
    {
        $payload = new \stdClass();
        $constraint = new ZipCode([
            'iso' => 'GB',
            'strict' => false,
            'caseSensitiveCheck' => false,
            'message' => 'Custom message',
            'groups' => 'Address',
            'payload' => $payload,
        ]);

        $this->assertSame('GB', $constraint->iso);
        $this->assertFalse($constraint->strict);
        $this->assertFalse($constraint->caseSensitiveCheck);
        $this->assertSame('Custom message', $constraint->message);
        $this->assertSame(['Address'], $constraint->groups);
        $this->assertSame($payload, $constraint->payload);
    }

    public function testNamedParametersAreSupported(): void
    {
        $constraint = new ZipCode(
            iso: 'FR',
            strict: false,
            caseSensitiveCheck: false,
            message: 'Another message',
            groups: ['Checkout']
        );

        $this->assertSame('FR', $constraint->iso);
        $this->assertFalse($constraint->strict);
        $this->assertFalse($constraint->caseSensitiveCheck);
        $this->assertSame('Another message', $constraint->message);
        $this->assertSame(['Checkout'], $constraint->groups);
    }

    public function testNamedParametersTakePrecedenceOverLegacyOptionsArray(): void
    {
        $constraint = new ZipCode(
            ['iso' => 'DE', 'strict' => true],
            iso: 'US',
            strict: false
        );

        $this->assertSame('US', $constraint->iso);
        $this->assertFalse($constraint->strict);
    }

    public function testUnknownLegacyOptionThrowsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ZipCode(['foo' => 'bar', 'iso' => 'FR']);
    }

    public function testLegacyStringOptionCannotBeCombinedWithNamedIso(): void
    {
        $this->expectException(InvalidOptionsException::class);
        new ZipCode('DE', iso: 'FR');
    }
}
