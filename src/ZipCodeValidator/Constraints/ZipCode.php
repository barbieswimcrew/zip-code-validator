<?php

namespace ZipCodeValidator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * Class ZipCode
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 * @author Martin Schindler
 * @package ZipCodeValidator\Constraints
 */
class ZipCode extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This value is not a valid ZIP code.';

    /**
     * @var string
     */
    public $iso;

    /**
     * @var string
     */
    public $getter;

    /**
     * @var bool
     */
    public $strict = true;

    /**
     * @var bool
     */
    public $ignoreEmpty = false;

    /**
     * @var bool
     */
    public $caseSensitiveCheck = true;

    /**
     * ZipCode constructor.
     * @param mixed $options
     */
    public function __construct($options = null)
    {
        if (null !== $options && !is_array($options)) {
            $options = array(
                'iso' => $options
            );
        }

        parent::__construct($options);

        if (null === $this->iso && null === $this->getter) {
            throw new MissingOptionsException(sprintf('Either the option "iso" or "getter" must be given for constraint %s', __CLASS__), array('iso', 'getter'));
        }
    }

}
