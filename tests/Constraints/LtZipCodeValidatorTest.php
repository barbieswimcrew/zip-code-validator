<?php

use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class LtZipCodeValidatorTest extends \PHPUnit\Framework\TestCase
{

    /** @var ZipCodeValidator */
    protected $validator;

    /**
     *
     */
    public function setUp()
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider getValidLithuanianZipCodes
     * @test
     * @param string $zipCode
     */
    public function testValidZipcodes($zipCode)
    {
        $constraint = new ZipCode('LT');

        /** @var ExecutionContext|PHPUnit_Framework_MockObject_MockObject $contextMock */
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
     *
     * @return array
     */
    public function getValidLithuanianZipCodes()
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
     * @test
     * @param string $zipCode
     */
    public function testInvalidZipcodes($zipCode)
    {
        $constraint = new ZipCode('LT');

        $violation = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violation->expects($this->once())->method('setParameter')->willReturnSelf();

        /** @var ExecutionContext|PHPUnit_Framework_MockObject_MockObject $contextMock */
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
     *
     * @return array
     */
    public function getInvalidLithuanianZipCodes()
    {
        return [
            ['1234'],
            ['LT12345'],
            ['LY-12345'],
            ['LT-1234'],
        ];
    }
}
