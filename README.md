# Constraint Class for international Zipcode Validation

[![Build Status](https://github.com/barbieswimcrew/zip-code-validator/actions/workflows/ci.yaml/badge.svg)](https://github.com/barbieswimcrew/zip-code-validator/actions/workflows/ci.yaml)
[![Downloads](https://img.shields.io/packagist/dt/barbieswimcrew/zip-code-validator.svg?style=flat-square)](https://packagist.org/packages/barbieswimcrew/zip-code-validator)
[![Latest stable version](https://img.shields.io/packagist/v/barbieswimcrew/zip-code-validator.svg?style=flat-square)](https://packagist.org/packages/barbieswimcrew/zip-code-validator)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/barbieswimcrew/zip-code-validator.svg?style=flat-square)](./composer.json)
[![GitHub stars](https://img.shields.io/github/stars/barbieswimcrew/zip-code-validator.svg?style=flat-square&label=Stars&style=flat-square)](https://github.com/barbieswimcrew/zip-code-validator/stargazers)
[![MIT licensed](https://img.shields.io/github/license/barbieswimcrew/zip-code-validator.svg?style=flat-square)](https://github.com/barbieswimcrew/zip-code-validator/blob/master/LICENSE)

## Installation
This package uses Composer, please checkout the [composer website](https://getcomposer.org) for more information.

The following command will install `zip-code-validator` into your project. It will also add a new entry in your `composer.json` and update the `composer.lock` as well.

```bash
$ composer require barbieswimcrew/zip-code-validator
```

> This package follows the PSR-4 convention names for its classes, which means you can easily integrate `zip-code-validator` classes loading in your own autoloader.

## What now?
For validating a zip code you need to instantiate a new ZipCode class provided by this package to set it as a constraint to your form field, for example:

```php
<?php
//...
$form = $this->createFormBuilder($address)
    ->add('zipcode', TextType::class, [
        'constraints' => [
            new ZipCodeValidator\Constraints\ZipCode([
                'iso' => 'DE'
            ])
        ]
    ))
    ->add('save', SubmitType::class, ['label' => 'Create Task'])
    ->getForm();
```

Another way would be to use the constraint as an annotation of a class property, for example:
```php
<?php

use ZipCodeValidator\Constraints\ZipCode;

class Address
{
    /**
     * @ZipCode(iso="DE")
     */
    protected $zipCode;
}
```

You can also use it as a PHP8 Attribute, with parameters passed as an array of options, for example:
```php
<?php

use ZipCodeValidator\Constraints\ZipCode;

class Address
{
    #[ZipCode(['iso'=>'DE'])
    protected $zipCode;
}
```

>  Please consider to inject a valid ISO 3166 2-letter country code (e.g. DE, US, FR)!

>  NOTE: This library validates against known zip code regex patterns and does not validate the existence of a zipcode.

### Use a getter to inject the country code dynamically

If you have a form, in which the user can select a country, you may want to validate the zip code dynamically.
In this case you can use the `getter` option instead:

```php
<?php

use ZipCodeValidator\Constraints\ZipCode;

class Address
{
    /**
     * @ZipCode(getter="getCountry")
     */
    protected $zipCode;

    protected $country;

    public function getCountry()
    {
        return $this->country;
    }
}
```

To disable that the validator throws an exception, when the zip code pattern is not available for a country,
you can set the `strict` option to `FALSE`.

```php
/**
 * @ZipCode(getter="getCountry", strict=false)
 */
protected $zipCode;
```

The validator will not validate empty strings and null values. To disallow them use the Symfony stock `NotNull` or `NotBlank` constraint in addition to `ZipCode`.

```php
/**
 * @ZipCode(getter="getCountry")
 * @NotBlank 
 */
protected $zipCode;
```

### Case insensitive zip code matching
In case you want to match the zip code in a case insensitive way you have to pass a `caseSensitiveCheck` parameter with `false` value via the constructor:
```php
$constraint = new ZipCode([
    'iso' => 'GB', 
    'caseSensitiveCheck' => false
]);

```
By the default the library is using case sensitive zip code matching.

## Copying / License
This repository is distributed under the MIT License (MIT). You can find the whole license text in the [LICENSE](LICENSE) file.
