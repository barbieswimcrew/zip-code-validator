<?php

use Mockery as m;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class GbZipCodeValidatorTest extends PHPUnit_Framework_TestCase
{
    /** @var ZipCodeValidator */
    protected $validator;

    /** @var \Symfony\Component\Validator\Context\ExecutionContextInterface|\Mockery\Mock */
    protected $context;

    public function setUp()
    {
        $this->validator = new ZipCodeValidator;

        $this->context = m::mock('\Symfony\Component\Validator\Context\ExecutionContext');

        $this->validator->initialize($this->context);
    }

    protected function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_validates_a_gb_zip_code_with_iso()
    {
        $constraint = new ZipCode('GB');

        // Test some variations
        $this->validator->validate('EC1A 1BB', $constraint);
        $this->validator->validate('W1A 0AX', $constraint);
        $this->validator->validate('M1 1AE', $constraint);
        $this->validator->validate('B33 8TH', $constraint);
        $this->validator->validate('CR2 6XH', $constraint);
        $this->validator->validate('DN55 1PT', $constraint);
        $this->validator->validate('BFPO 801', $constraint);
        $this->validator->validate('GIR 0AA', $constraint);
        $this->validator->validate('PH1 5RB', $constraint);
        $this->validator->validate('CO4 3SQ', $constraint);
        $this->validator->validate('CO4 3SQ', $constraint);
    }

    /** @test */
    public function it_wont_validate_an_invalid_gb_zip_code()
    {
        $value = 'invalid';
        $constraint = new ZipCode('GB');

        // Non-mocked context object due to sheer complexity. Might need a refactor
        $translator = new \Symfony\Component\Translation\Translator('en-US');
        $context = new \Symfony\Component\Validator\Context\ExecutionContext(
            new \Symfony\Component\Validator\Validator\RecursiveValidator(
                new \Symfony\Component\Validator\Context\ExecutionContextFactory($translator),
                new \Symfony\Component\Validator\Tests\Fixtures\FakeMetadataFactory(),
                new \Symfony\Component\Validator\ConstraintValidatorFactory()
            ),
            null,
            $translator
        );

        $context->setConstraint($constraint);
        $this->validator->initialize($context);

        $this->validator->validate($value, $constraint);

        $this->assertEquals(
            $constraint->message,
            $context->getViolations()->get(0)->getMessage()
        );
    }

    /** @test */
    public function it_wont_validate_an_valid_gb_zip_code_with_extra_leading_chars()
    {
        $value = 'XEC1A 1BB';
        $constraint = new ZipCode('GB');

        // Non-mocked context object due to sheer complexity. Might need a refactor
        $translator = new \Symfony\Component\Translation\Translator('en-US');
        $context = new \Symfony\Component\Validator\Context\ExecutionContext(
            new \Symfony\Component\Validator\Validator\RecursiveValidator(
                new \Symfony\Component\Validator\Context\ExecutionContextFactory($translator),
                new \Symfony\Component\Validator\Tests\Fixtures\FakeMetadataFactory(),
                new \Symfony\Component\Validator\ConstraintValidatorFactory()
            ),
            null,
            $translator
        );

        $context->setConstraint($constraint);
        $this->validator->initialize($context);

        $this->validator->validate($value, $constraint);

        $this->assertEquals(
            $constraint->message,
            $context->getViolations()->get(0)->getMessage()
        );
    }

    /** @test */
    public function it_wont_validate_an_valid_gb_zip_code_with_extra_trainling_chars()
    {
        $value = 'EC1A 1BBX';
        $constraint = new ZipCode('GB');

        // Non-mocked context object due to sheer complexity. Might need a refactor
        $translator = new \Symfony\Component\Translation\Translator('en-US');
        $context = new \Symfony\Component\Validator\Context\ExecutionContext(
            new \Symfony\Component\Validator\Validator\RecursiveValidator(
                new \Symfony\Component\Validator\Context\ExecutionContextFactory($translator),
                new \Symfony\Component\Validator\Tests\Fixtures\FakeMetadataFactory(),
                new \Symfony\Component\Validator\ConstraintValidatorFactory()
            ),
            null,
            $translator
        );

        $context->setConstraint($constraint);
        $this->validator->initialize($context);

        $this->validator->validate($value, $constraint);

        $this->assertEquals(
            $constraint->message,
            $context->getViolations()->get(0)->getMessage()
        );
    }
}

class TestGbZipCodeConstraint extends ZipCode
{
    /** @var string */
    public $getter = 'myValidationMethod';
}

class TestGbObject
{
    /** @var string */
    protected $iso;

    /**
     * TestObject constructor.
     *
     * @param string $iso
     */
    public function __construct($iso)
    {
        $this->iso = $iso;
    }

    /**
     * @return string
     */
    public function myValidationMethod()
    {
        return $this->iso;
    }
}
