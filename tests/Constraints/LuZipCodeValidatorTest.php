<?php

declare(strict_types=1);

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class LuZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * @dataProvider getValidLuxembourgZipCodes
     */
    public function testValidZipcodes(string $zipCode): void
    {
        $constraint = new ZipCode('LU');

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
     * Valid Luxembourg postal codes are four-digit numbers, optionally prefixed with "L-".
     * @see https://www.post.lu/fr/particuliers/colis-courrier/bien-rediger-une-adresse
     */
    public static function getValidLuxembourgZipCodes(): array
    {
        return [
            ['8211'],
            ['L-8211'],
            ['1234'],
            ['L-1234'],
        ];
    }

    /**
     * @dataProvider getInvalidLuxembourgZipCodes
     */
    public function testInvalidZipcodes(string $zipCode): void
    {
        $constraint = new ZipCode('LU');

        $violation = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violation->expects($this->once())->method('setParameter')->willReturnSelf();

        /** @var ExecutionContext|MockObject $contextMock */
        $contextMock = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        $contextMock->expects($this->once())->method('buildViolation')->willReturn($violation);
        $contextMock->setConstraint($constraint);

        $this->validator->initialize($contextMock);
        $this->validator->validate($zipCode, $constraint);
    }

    /**
     * Valid Luxembourg postal codes are four-digit numbers, optionally prefixed with "L-".
     * @see https://www.post.lu/fr/particuliers/colis-courrier/bien-rediger-une-adresse
     */
    public static function getInvalidLuxembourgZipCodes(): array
    {
        return [
            ['123'],
            ['12345'],
            ['L1234'],
        ];
    }
}
