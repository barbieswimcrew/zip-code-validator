<?php

namespace ZipCodeValidator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 * @author Martin Schindler
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
class ZipCode extends Constraint
{
    public string $message = 'This value is not a valid ZIP code.';
    public ?string $iso = null;
    public ?string $getter = null;
    public bool $strict = true;
    public bool $caseSensitiveCheck = true;

    public function __construct(mixed $options = null, array $groups = null, mixed $payload = null)
    {
        if (is_string($options)) {
            $options = array(
                'iso' => $options
            );
        }

        parent::__construct($options, $groups, $payload);

        if (null === $this->iso && null === $this->getter) {
            throw new MissingOptionsException(sprintf('Either the option "iso" or "getter" must be given for constraint %s', __CLASS__), ['iso', 'getter']);
        }
    }

}
