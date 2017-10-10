<?php

use Mockery as m;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class ZipCodeValidatorTest extends PHPUnit_Framework_TestCase
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
    public function it_validates_a_zip_code_with_iso()
    {
        $constraint = new ZipCode('HK');

        // Test some integers
        $this->validator->validate(999077, $constraint);

        $constraint->iso = 'KE';
        $this->validator->validate(12345, $constraint);

        // Test some strings
        $constraint->iso = 'MU';
        $this->validator->validate('15325', $constraint);
        $this->validator->validate('153BU123', $constraint);

        $constraint->iso = 'NL';
        $this->validator->validate('1234AB', $constraint);
        $this->validator->validate('1234 AB', $constraint);

        $constraint->iso = 'PN';
        $this->validator->validate('PCRN 1ZZ', $constraint);
    }

    /** @test */
    public function it_validates_a_zip_code_with_getter()
    {
        $this->context->shouldReceive('getObject')->once()
            ->andReturn(new TestObject('VN'));

        $this->validator->initialize($this->context);

        $this->validator->validate(123456, new TestZipCodeConstraint);
    }

    /** @test */
    public function it_wont_validate_an_invalid_zip_code()
    {
        $value = 'invalid';
        $constraint = new ZipCode('HK');

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

    /**
     * @test
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function it_throws_an_exception_on_invalid_constraint_subclass()
    {
        /** @var \Symfony\Component\Validator\Constraint $constraint */
        $constraint = m::mock('\Symfony\Component\Validator\Constraint');

        $this->validator->validate('dummy', $constraint);
    }

    /**
     * Note: \BadMethodCallException is due to context being mocked. When it gets the exception, it will have processed
     * through the validate method and into the no pattern matching block.
     *
     * @see it_wont_validate_an_invalid_zip_code
     *
     * @test
     * @expectedException \BadMethodCallException
     */
    public function it_throws_an_exception_on_empty_value()
    {
        $constraint = new ZipCode('HK');
        $constraint->ignoreEmpty = false;

        $this->validator->validate('', $constraint);
    }

    /** @test */
    public function it_does_nothing_on_empty_value_if_ignore_empty_is_true()
    {
        $constraint = new ZipCode('HK');
        $constraint->ignoreEmpty = true;

        $this->validator->validate('', $constraint);
    }

    /** @test */
    public function it_returns_blank_on_empty_iso()
    {
        $this->context->shouldReceive('getObject')->once()
            ->andReturn(new TestObject(null));

        $this->validator->initialize($this->context);

        $this->validator->validate('dummy', new TestZipCodeConstraint);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function it_throws_an_exception_on_invalid_iso_if_strict_mode_is_true()
    {
        $this->context->shouldReceive('getObject')->once()
            ->andReturn(new TestObject('non-existing iso'));

        $this->validator->initialize($this->context);

        $this->validator->validate('dummy', new TestZipCodeConstraint);
    }
}

class TestZipCodeConstraint extends ZipCode
{
    /** @var string */
    public $getter = 'myValidationMethod';
}

class TestObject
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
