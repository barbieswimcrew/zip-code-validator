<?php

use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;


/**
 * Class PaZipCodeValidatorTest
 */
class PaZipCodeValidatorTest extends \PHPUnit\Framework\TestCase
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
     * This test verifies that all known Panama codes are valid.
     *
     * @dataProvider getPanamaZipCodes
     * @test
     * @param string $zipCode
     */
    public function testZipcodes($zipCode)
    {
        $constraint = new ZipCode('PA');

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
     * used postal codes
     * from https://en.wikipedia.org/wiki/Postal_codes_in_Panama
     *
     * @return array
     */
    public function getPanamaZipCodes()
    {
        return [
            ['0101'],
            ['0401'],
            ['0201'],
            ['0301'],
            ['0501'],
            ['0601'],
            ['0701'],
            ['0801'],
            ['1001'],
            ['0901'],
        ];
    }

}
