<?php

namespace ZipCodeValidator\Constraints;

use Symfony\Component\Validator\Constraint;

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
     * @var mixed|null
     */
    private $iso;

    /**
     * ZipCode constructor.
     * @param string $iso
     * @param null $options
     */
    public function __construct($iso, $options = null)
    {
        parent::__construct($options);
        $this->iso = $iso;
    }

    /**
     * Getter for iso property
     * @author Martin Schindler
     * @return mixed|null
     */
    public function getIso()
    {
        return $this->iso;
    }
}