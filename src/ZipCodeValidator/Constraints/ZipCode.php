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

    public $iso;

    /**
     * ZipCode constructor.
     * @param null $options
     */
    public function __construct($options = null)
    {

        if (null !== $options && !is_array($options)) {
            $options = array(
                'iso' => $options
            );
        }

        parent::__construct($options);

        if (null === $this->iso) {
            throw new MissingOptionsException(sprintf('The option "iso" must be given for constraint %s', __CLASS__), array('iso'));
        }
    }

}