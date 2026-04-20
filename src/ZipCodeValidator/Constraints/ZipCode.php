<?php

declare(strict_types=1);

namespace ZipCodeValidator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
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

    public function __construct(
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
        ?string $iso = null,
        ?string $getter = null,
        ?bool $strict = null,
        ?bool $caseSensitiveCheck = null,
        ?string $message = null
    ) {
        if (\is_string($options)) {
            if (null !== $iso) {
                throw new InvalidOptionsException(
                    'Cannot pass both positional string $options and named "iso". Use one style.',
                    ['options', 'iso']
                );
            }

            $options = ['iso' => $options];
        } elseif (null === $options) {
            $options = [];
        } elseif (!\is_array($options)) {
            throw new InvalidOptionsException(sprintf('The options "%s" do not exist in constraint "%s".', 'options', __CLASS__), ['options']);
        }

        $resolvedOptions = [
            'iso' => $iso,
            'getter' => $getter,
            'strict' => $strict,
            'caseSensitiveCheck' => $caseSensitiveCheck,
            'message' => $message,
            'groups' => $groups,
            'payload' => $payload,
        ];

        $invalidOptions = array_values(array_filter(array_keys($options), fn ($option) => !\in_array($option, array_keys($resolvedOptions), true)));
        if ([] !== $invalidOptions) {
            throw new InvalidOptionsException(
                sprintf('The options "%s" do not exist in constraint "%s".', implode('", "', $invalidOptions), __CLASS__),
                $invalidOptions
            );
        }

        foreach ($resolvedOptions as $option => $resolvedValue) {
            if (null !== $resolvedValue || !\array_key_exists($option, $options)) {
                continue;
            }

            $resolvedOptions[$option] = 'groups' === $option ? (array) $options[$option] : $options[$option];
        }

        parent::__construct(null, $resolvedOptions['groups'], $resolvedOptions['payload']);

        unset($resolvedOptions['groups'], $resolvedOptions['payload']);

        foreach ($resolvedOptions as $option => $resolvedValue) {
            if (null === $resolvedValue) {
                continue;
            }

            $this->{$option} = $resolvedValue;
        }

        if (null === $this->iso && null === $this->getter) {
            throw new MissingOptionsException(sprintf('Either the option "iso" or "getter" must be given for constraint %s', __CLASS__), ['iso', 'getter']);
        }
    }
}
