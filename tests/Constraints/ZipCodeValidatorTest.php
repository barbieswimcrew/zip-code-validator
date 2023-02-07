<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class ZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    /** @var ExecutionContextInterface|MockObject */
    protected $contextMock;

    public function setUp(): void
    {
        $this->contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider validZipCodes
     * @doesNotPerformAssertions
     */
    public function testZipCodeValidationWithIso(string|int $isoCode, string|int|null $value): void
    {
        $constraint = new ZipCode($isoCode);
        $this->validator->validate($value, $constraint);
    }

    /**
     * @dataProvider
     */
    public function validZipCodes(): array
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

    public function testValidZipCodeValidationWithGetter(): void
    {
        /** @var ZipCode|MockObject $constraintMock */
        $constraintMock = $this->getMockBuilder(ZipCode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $constraintMock->iso = null;
        $constraintMock->getter = 'myValidationMethod';

        $gbObject = new \ZipCodeValidator\Tests\Fixtures\IsoObject('VN');

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

    public function testUnexpectedTypeException(): void
    {
        $constraint = $this->getMockBuilder(Constraint::class)->disableOriginalConstructor()->getMock();
        $this->expectException(UnexpectedTypeException::class);
        $this->validator = new ZipCodeValidator();
        $this->validator->validate('FOO', $constraint);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testValidationIgnoresBlankValues(): void
    {
        $constraint = new ZipCode('HK');

        $this->validator->validate('', $constraint);
        $this->validator->validate(null, $constraint);
    }

    public function testValidationReturnsNothingOnEmptyIso(): void
    {
        /** @var ZipCode|MockObject $constraintMock */
        $constraintMock = $this->getMockBuilder(ZipCode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $constraintMock->iso = '';
        $constraintMock->getter = 'myValidationMethod';

        $isoObject = new \ZipCodeValidator\Tests\Fixtures\IsoObject('');

        /** @var ExecutionContext|MockObject $this->contextMock */
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

    public function testConstraintDefinitionExceptionWhenInvalidIsoInStrictMode(): void
    {
        $constraint = new ZipCode('FOO');
        $this->expectException(ConstraintDefinitionException::class);
        $this->validator->validate('dummy', $constraint);
    }

}
