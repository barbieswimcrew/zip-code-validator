<?php

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class ZipCodeValidatorTest extends \PHPUnit\Framework\TestCase
{
    /** @var ZipCodeValidator */
    protected $validator;

    /** @var \Symfony\Component\Validator\Context\ExecutionContextInterface|PHPUnit_Framework_MockObject_MockObject */
    protected $contextMock;

    public function setUp()
    {
        $this->contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @param string $isoCode
     * @param string $value
     *
     * @dataProvider validZipCodes
     * @doesNotPerformAssertions
     */
    public function testZipCodeValidationWithIso($isoCode, $value)
    {
        $constraint = new ZipCode($isoCode);
        $this->validator->validate($value, $constraint);
    }

    /**
     * @dataProvider
     */
    public function validZipCodes()
    {
        return [
            ['HK', 999077],
            ['KE', 12345],
            ['MU', '15325'],
            ['MU', '153BU123'],
            ['NL', '1234AB'],
            ['NL', '1234 AB'],
            ['PN', 'PCRN 1ZZ']
        ];
    }

    /** @test */
    public function testValidZipCodeValidationWithGetter()
    {
        /** @var ZipCode|PHPUnit_Framework_MockObject_MockObject $constraintMock */
        $constraintMock = $this->getMockBuilder(ZipCode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $constraintMock->iso = null;
        $constraintMock->getter = 'myValidationMethod';

        $gbObject = new \Tests\Fixtures\IsoObject('VN');

        $this->contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->contextMock->expects($this->once())
            ->method('getObject')
            ->willReturn($gbObject);

        $this->contextMock->setConstraint($constraintMock);
        $this->validator->initialize($this->contextMock);
        $this->validator->validate(123456, $constraintMock);
    }

    public function testUnexpectedTypeException()
    {
        $constraint = $this->getMockBuilder(Constraint::class)->disableOriginalConstructor()->getMock();
        $this->expectException(UnexpectedTypeException::class);
        $this->validator = new ZipCodeValidator();
        $this->validator->validate('FOO', $constraint);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testValidationIgnoresBlankValues()
    {
        $constraint = new ZipCode('HK');

        $this->validator->validate('', $constraint);
        $this->validator->validate(null, $constraint);
    }

    /** @test */
    public function testValidationReturnsNothingOnEmptyIso()
    {
        /** @var ZipCode|PHPUnit_Framework_MockObject_MockObject $constraintMock */
        $constraintMock = $this->getMockBuilder(ZipCode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $constraintMock->iso = '';
        $constraintMock->getter = 'myValidationMethod';

        $isoObject = new \Tests\Fixtures\IsoObject('');

        /** @var ExecutionContext|PHPUnit_Framework_MockObject_MockObject $this->contextMock */
        $this->contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->contextMock->expects($this->once())
            ->method('getObject')
            ->willReturn($isoObject);

        $this->contextMock->setConstraint($constraintMock);
        $this->validator->initialize($this->contextMock);

        $this->validator->validate('dummy', $constraintMock);
    }

    public function testConstraintDefinitionExceptionWhenInvalidIsoInStrictMode()
    {
        $constraint = new ZipCode('FOO');
        $this->expectException(ConstraintDefinitionException::class);
        $this->validator->validate('dummy', $constraint);
    }

}
