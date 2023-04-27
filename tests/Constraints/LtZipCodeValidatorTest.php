<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class LtZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider getValidLithuanianZipCodes
     */
    public function testValidZipcodes(string $zipCode): void
    {
        $constraint = new ZipCode('LT');

        /** @var ExecutionContext|MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        # be sure that buildViolation never gets called
        $contextMock->expects($this->never())->method('buildViolation');
        $contextMock->setConstraint($constraint);

        $this->validator->initialize($contextMock);
        $this->validator->validate($zipCode, $constraint);
    }

    /**
     * Valid Lithuanian postal codes are five-digit numbers, optionally prefixed with "LT-".
     * @see https://en.wikipedia.org/wiki/Postal_codes_in_Lithuania
     */
    public static function getValidLithuanianZipCodes(): array
    {
        return [
            ['12345'],
            ['01234'],
            ['98765'],
            ['LT-12345'],
            ['LT-01234'],
            ['LT-98765'],
        ];
    }

    /**
     * @dataProvider getInvalidLithuanianZipCodes
     */
    public function testInvalidZipcodes(string $zipCode): void
    {
        $constraint = new ZipCode('LT');

        $violation = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violation->expects($this->once())->method('setParameter')->willReturnSelf();

        /** @var ExecutionContext|MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        # be sure that buildViolation never gets called
        $contextMock->expects($this->once())->method('buildViolation')->willReturn($violation);
        $contextMock->setConstraint($constraint);

        $this->validator->initialize($contextMock);
        $this->validator->validate($zipCode, $constraint);
    }

    /**
     * Valid Lithuanian postal codes are five-digit numbers, optionally prefixed with "LT-".
     * @see https://en.wikipedia.org/wiki/Postal_codes_in_Lithuania
     */
    public static function getInvalidLithuanianZipCodes(): array
    {
        return [
            ['1234'],
            ['LT12345'],
            ['LY-12345'],
            ['LT-1234'],
        ];
    }
}
