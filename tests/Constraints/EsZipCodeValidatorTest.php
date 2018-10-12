<?php

use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class EsZipCodeValidatorTest extends \PHPUnit\Framework\TestCase
{
    /** @var ZipCodeValidator */
    protected $validator;

    public function setUp()
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider esValidZipCodes
     * @param string $zipCode
     */
    public function testValidationOfEsZipCode($zipCode)
    {
        $constraint = new ZipCode('ES');

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
     * @param string $zipcode
     */
    public function testValidationErrorWithInvalidEsZipCode($zipcode)
    {
        $constraint = new ZipCode('ES');

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
    public function esInvalidZipCodes()
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
