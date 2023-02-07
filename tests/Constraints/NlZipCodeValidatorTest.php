<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class NlZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testValidationOfNlZipWithIsoCode(): void
    {
        $constraint = new ZipCode('NL');

        // Test some variations
        $this->validator->validate('1000AA', $constraint);
        $this->validator->validate('1000 AA', $constraint);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testValidationOfNlZipWithIsoCodeAnSmallCaps(): void
    {
	    $constraint = new ZipCode([
                'iso'                => 'NL',
                'caseSensitiveCheck' => false
	    ]);

        // Test some variations
        $this->validator->validate('1000aa', $constraint);
        $this->validator->validate('1000 aa', $constraint);
    }

    public function testValidationErrorWithInvalidNlZipCode(): void
    {
        $value = "1000";
        $constraint = new ZipCode('NL');

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
}
