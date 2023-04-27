<?php

namespace ZipCodeValidator\Tests\Constraints;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;


/**
 * Class PaZipCodeValidatorTest
 */
class PaZipCodeValidatorTest extends TestCase
{
    protected ZipCodeValidator $validator;

    public function setUp(): void
    {
        $this->validator = new ZipCodeValidator;
    }

    /**
     * This test verifies that all known Panama codes are valid.
     *
     * @dataProvider getPanamaZipCodes
     */
    public function testZipcodes(string $zipCode): void
    {
        $constraint = new ZipCode('PA');

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
     * from https://en.wikipedia.org/wiki/Postal_codes_in_Panama
     */
    public static function getPanamaZipCodes(): array
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
