<?php

declare(strict_types=1);

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class CuZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider chValidZipCodes
     */
    public function testValidationOfCuZipCode(string $zipCode): void
    {
        $constraint = new ZipCode('CU');

        /** @var ExecutionContext|MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        //be sure that buildViolation never gets called
        $contextMock->expects($this->never())->method('buildViolation');

        $contextMock->setConstraint($constraint);
        $this->validator->initialize($contextMock);

        // Test some variations
        $this->validator->validate($zipCode, $constraint);
    }

    public static function chValidZipCodes(): array
    {
        return [
            ['11300'],
            ['22700'],
            ['33400'],
            ['67600'],
        ];
    }

    /**
     * @dataProvider cuInvalidZipCodes
     */
    public function testValidationErrorWithInvalidCuZipCode(string $zipcode): void
    {
        $constraint = new ZipCode('CU');

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

    public static function cuInvalidZipCodes(): array
    {
        return [
            ['1420'],
            ['CA123'],
            ['CACAC'],
            ['ch1289'],
            ['14-241'],
        ];
    }
}
