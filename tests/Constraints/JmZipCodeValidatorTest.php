<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

/**
 * Class JmZipCodeValidatorTest
 */
class JmZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * This test verifies that all known Jamaica codes are valid.
     *
     * @dataProvider getValidJamaicaZipCodes
     */
    public function testValidZipcodes(string $zipCode): void
    {
        $constraint = new ZipCode('JM');

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
     * used postal codes
     * from https://en.wikipedia.org/wiki/Postal_codes_in_Jamaica
     */
    public static function getValidJamaicaZipCodes(): array
    {
        return [
            ['KN'],
            ['AW'],
            ['CE'],
            ['TS'],
            ['PD'],
            ['MY'],
        ];
    }

    /**
     * This test verifies that all known Jamaica codes are valid.
     *
     * @dataProvider getInvalidJamaicaZipCodes
     */
    public function testInvalidZipcodes(string $zipCode): void
    {
        $constraint = new ZipCode('JM');

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
     * used postal codes
     * from https://en.wikipedia.org/wiki/Postal_codes_in_Jamaica
     */
    public static function getInvalidJamaicaZipCodes(): array
    {
        return [
            ['12'],
            ['\W'],
            ['A1'],
            ['0z'],
        ];
    }
}
