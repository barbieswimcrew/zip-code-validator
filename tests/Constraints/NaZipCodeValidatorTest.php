<?php

declare(strict_types=1);

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class NaZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider naValidZipCodes
     */
    public function testValidationOfNaZipCode(string $zipCode): void
    {
        $constraint = new ZipCode('NA');

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

    public static function naValidZipCodes(): array
    {
        return [
            ['13006'],
            ['22002'],
            ['19001'],
            ['10033'],
        ];
    }

    /**
     * @dataProvider naInvalidZipCodes
     */
    public function testValidationErrorWithInvalidNaZipCode(string $zipcode): void
    {
        $constraint = new ZipCode('NA');

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

    public static function naInvalidZipCodes(): array
    {
        return [
            ['12345'],
            ['14-20'],
            ['110655'],
            ['024567'],
            ['PP023'],
            ['AC-70'],
        ];
    }

}
