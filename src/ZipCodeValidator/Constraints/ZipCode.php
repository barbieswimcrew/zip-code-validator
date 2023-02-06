<?php

namespace ZipCodeValidator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 * @author Martin Schindler
 */
class ZipCode extends Constraint
{
    public string $message = 'This value is not a valid ZIP code.';
    public ?string $iso = null;
    public ?string $getter = null;
    public bool $strict = true;
    public bool $caseSensitiveCheck = true;

    public function __construct(mixed $options = null)
    {
        if (null !== $options && !is_array($options)) {
            $options = array(
                'iso' => $options
            );
        }

        parent::__construct($options);

        if (null === $this->iso && null === $this->getter) {
            throw new MissingOptionsException(sprintf('Either the option "iso" or "getter" must be given for constraint %s', __CLASS__), ['iso', 'getter']);
        }
    }

}
