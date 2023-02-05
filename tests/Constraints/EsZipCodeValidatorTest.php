<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class EsZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider esValidZipCodes
     */
    public function testValidationOfEsZipCode(string $zipCode): void
    {
        $constraint = new ZipCode('ES');

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

    /**
     * @return array
     */
    public function esValidZipCodes()
    {
        return [
            ['08020'],
            ['11231'],
            ['52006'],
            ['49739'],
        ];
    }

    /**
     * @dataProvider esInvalidZipCodes
     */
    public function testValidationErrorWithInvalidEsZipCode(string $zipcode): void
    {
        $constraint = new ZipCode('ES');

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

    public function esInvalidZipCodes(): array
    {
        return [
            ['53456'],
            ['0413A'],
            ['024567'],
            ['es123'],
            ['2341'],
        ];
    }
}
