<?php

declare(strict_types=1);

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class HkZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider hkValidZipCodes
     */
    public function testValidationOfHkZipCode(string $zipCode): void
    {
        $constraint = new ZipCode('HK');

        /** @var ExecutionContext|MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextMock->expects($this->never())->method('buildViolation');

        $contextMock->setConstraint($constraint);
        $this->validator->initialize($contextMock);

        $this->validator->validate($zipCode, $constraint);
    }

    public static function hkValidZipCodes(): array
    {
        return [
            ['999077'],
            ['000'],
            ['0000'],
            ['000000'],
            ['HK'],
            ['HKG'],
        ];
    }

    /**
     * @dataProvider hkInvalidZipCodes
     */
    public function testValidationErrorWithInvalidHkZipCode(string $zipcode): void
    {
        $constraint = new ZipCode('HK');

        $violationBuilderMock = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $violationBuilderMock->expects($this->once())->method('setParameter')->willReturnSelf();

        /** @var ExecutionContext|MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock->expects($this->once())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($violationBuilderMock);

        $contextMock->setConstraint($constraint);
        $this->validator->initialize($contextMock);
        $this->validator->validate($zipcode, $constraint);
    }

    public static function hkInvalidZipCodes(): array
    {
        return [
            ['12345'],
            ['1234'],
            ['HK1'],
            ['H K'],
            ['ABCD'],
        ];
    }
}
