<?php

use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class ChZipCodeValidatorTest extends \PHPUnit\Framework\TestCase
{
    /** @var ZipCodeValidator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider chValidZipCodes
     * @param string $zipCode
     */
    public function testValidationOfChZipCode($zipCode)
    {
        $constraint = new ZipCode('CH');

        /** @var ExecutionContext|PHPUnit_Framework_MockObject_MockObject $contextMock */
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
    public function chValidZipCodes()
    {
        return [
            ['1000'],
            ['3000'],
            ['3250'],
            ['9658'],
        ];
    }

    /**
     * @dataProvider chInvalidZipCodes
     * @param string $zipcode
     */
    public function testValidationErrorWithInvalidChZipCode($zipcode)
    {
        $constraint = new ZipCode('CH');

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
        $this->validator->validate($zipcode, $constraint);
    }

    /**
     * @return array
     */
    public function chInvalidZipCodes()
    {
        return [
            ['0000'],
            ['0099'],
            ['024567'],
            ['ch128'],
            ['2-341'],
        ];
    }


}
