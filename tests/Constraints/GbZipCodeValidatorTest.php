<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class GbZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider GbZipCodes
     */
    public function testValidationOfGbZipCodeWithIso($zipCode): void
    {
        $constraint = new ZipCode('GB');

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
     * @dataProvider
     */
    public function gbZipCodes(): array
    {
        return [
            ['EC1A 1BB'],
            ['W1A 0AX'],
            ['M1 1AE'],
            ['B33 8TH'],
            ['CR2 6XH'],
            ['DN55 1PT'],
            ['BFPO 801'],
            ['GIR 0AA'],
            ['PH1 5RB'],
            ['CO4 3SQ'],
            ['CO4 3SQ'],
        ];
    }

    /**
     * @dataProvider gbZipCodesSmallCaps
     */
    public function testValidationOfGbZipCodeWithIsoAndSmallCaps(string $zipCode): void
    {
        $constraint = new ZipCode([
            'iso'                => 'GB',
            'caseSensitiveCheck' => false
        ]);

        /** @var ExecutionContext|MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        //be sure that buildViolation never gets called
        $contextMock->expects($this->never())->method('buildViolation');

        $contextMock->setConstraint($constraint);
        $this->validator->initialize($contextMock);

        $this->validator->validate($zipCode, $constraint);
    }

    /**
     * @dataProvider
     */
    public function gbZipCodesSmallCaps(): array
    {
        return [
            ['ec1a 1bb'],
            ['w1a 0ax'],
            ['m1 1ae'],
            ['b33 8th'],
            ['cr2 6xh'],
            ['dn55 1pt'],
            ['bfpo 801'],
            ['gir 0aa'],
            ['ph1 5rb'],
            ['co4 3sq']
        ];
    }

    public function testValidationErrorWithInvalidGbZipCode(): void
    {
        $value = 'invalid';
        $constraint = new ZipCode('GB');

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
        $this->validator->validate($value, $constraint);
    }

    public function testValidationErrorWithValidGbZipCodeWithExtraLeadingChars(): void
    {
        $value = 'XEC1A 1BB';
        $constraint = new ZipCode('GB');

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
        $this->validator->validate($value, $constraint);
    }

    public function testValidationErrorWithValidGbZipCodeWithExtraTrailingChars(): void
    {
        $value = 'EC1A 1BBX';
        $constraint = new ZipCode('GB');

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
        $this->validator->validate($value, $constraint);
    }

    public function testEmptyIsoWithValidZipCodeWillCallGetterMethodOnObject(): void
    {
        $value = 'EC1A 1BB';
        /** @var ZipCode|MockObject $constraintMock */
        $constraintMock = $this->getMockBuilder(ZipCode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $constraintMock->iso = null;
        $constraintMock->getter = 'myValidationMethod';

        $gbObject = new \ZipCodeValidator\Tests\Fixtures\IsoObject('GB');

        /** @var ExecutionContext|MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock->expects($this->once())
            ->method('getObject')
            ->willReturn($gbObject);

        $contextMock->setConstraint($constraintMock);
        $this->validator->initialize($contextMock);
        $this->validator->validate($value, $constraintMock);
    }

    public function testConstraintDefinitionExceptionWhenCallableMethodNotExists(): void
    {
        $value = 'EC1A 1BB';
        /** @var ZipCode|MockObject $constraintMock */
        $constraintMock = $this->getMockBuilder(ZipCode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $constraintMock->iso = null;
        $constraintMock->getter = 'myFooMethod'; // not existing in GbObject

        $gbObject = new \ZipCodeValidator\Tests\Fixtures\IsoObject('GB');

        /** @var ExecutionContext|MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock->expects($this->once())
            ->method('getObject')
            ->willReturn($gbObject);

        $contextMock->setConstraint($constraintMock);
        $this->validator->initialize($contextMock);

        $exceptionMessage = 'Method "myFooMethod" used as iso code getter does not exist in class ' .
            get_class($gbObject);

        $this->expectException(ConstraintDefinitionException::class);
        $this->expectExceptionMessage($exceptionMessage);
        $this->validator->validate($value, $constraintMock);
    }
}
