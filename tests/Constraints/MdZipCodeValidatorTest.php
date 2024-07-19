<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class MdZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider getValidMoldovaZipCodes
     */
    public function testValidZipcodes(string $zipCode): void
    {
        $constraint = new ZipCode('MD');

        /** @var ExecutionContext|MockObject $contextMock */
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
     * Valid Moldova postal codes are four-digit numbers, optionally prefixed with "MD-".
     * @see https://en.wikipedia.org/wiki/Postal_codes_in_Moldova
     */
    public static function getValidMoldovaZipCodes(): array
    {
        return [
            ['1234'],
            ['0123'],
            ['9876'],
            ['MD-1234'],
            ['MD-0123'],
            ['MD-9876'],
        ];
    }

    /**
     * @dataProvider getInvalidMoldovaZipCodes
     */
    public function testInvalidZipcodes(string $zipCode): void
    {
        $constraint = new ZipCode('MD');

        $violation = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violation->expects($this->once())->method('setParameter')->willReturnSelf();

        /** @var ExecutionContext|MockObject $contextMock */
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
     * Valid Moldova postal codes are four-digit numbers, optionally prefixed with "MD-".
     * @see https://en.wikipedia.org/wiki/Postal_codes_in_Moldova
     */
    public static function getInvalidMoldovaZipCodes(): array
    {
        return [
            ['123'],
            ['12345'],
            ['MD12345'],
            ['MO-12345'],
            ['MD-12345'],
        ];
    }
}
