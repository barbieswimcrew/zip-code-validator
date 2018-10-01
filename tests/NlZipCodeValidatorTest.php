<?php

use Mockery as m;
use ZipCodeValidator\Constraints\ZipCode;
use ZipCodeValidator\Constraints\ZipCodeValidator;

class NlZipCodeValidatorTest extends PHPUnit_Framework_TestCase
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
    public function it_validates_a_nl_zip_code_with_iso()
    {
        $constraint = new ZipCode('NL');

        // Test some variations
        $this->validator->validate('1000AA', $constraint);
        $this->validator->validate('1000 AA', $constraint);
    }

    /** @test */
    public function it_validates_a_nl_zip_code_with_iso_and_small_caps()
    {
	    $constraint = new ZipCode([
                'iso'                => 'NL',
                'caseSensitiveCheck' => false
	    ]);

        // Test some variations
        $this->validator->validate('1000aa', $constraint);
        $this->validator->validate('1000 aa', $constraint);
    }

    /** @test */
    public function it_wont_validate_an_invalid_nl_zip_code()
    {
        $value = '1000';
        $constraint = new ZipCode('NL');

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

class TestNLZipCodeConstraint extends ZipCode
{
    /** @var string */
    public $getter = 'myValidationMethod';
}

class TestNLObject
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
