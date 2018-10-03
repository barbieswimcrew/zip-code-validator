<?php

use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class NlZipCodeValidatorTest extends \PHPUnit\Framework\TestCase
{
    /** @var ZipCodeValidator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testValidationOfNlZipWithIsoCode()
    {
        $constraint = new ZipCode('NL');

        // Test some variations
        $this->validator->validate('1000AA', $constraint);
        $this->validator->validate('1000 AA', $constraint);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testValidationOfNlZipWithIsoCodeAnSmallCaps()
    {
	    $constraint = new ZipCode([
                'iso'                => 'NL',
                'caseSensitiveCheck' => false
	    ]);

        // Test some variations
        $this->validator->validate('1000aa', $constraint);
        $this->validator->validate('1000 aa', $constraint);
    }

    public function testValidationErrorWithInvalidNlZipCode()
    {
        $value = "1000";
        $constraint = new ZipCode('NL');

        $violationBuilderMock = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $violationBuilderMock->expects($this->once())->method('setParameter')->willReturnSelf();

        /** @var ExecutionContext|PHPUnit_Framework_MockObject_MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock->expects($this->exactly(1))
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($violationBuilderMock);

        $contextMock->setConstraint($constraint);
        $this->validator->initialize($contextMock);
        $this->validator->validate($value, $constraint);
    }
}
