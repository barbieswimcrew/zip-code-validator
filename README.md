# Constraint Class for international Zipcode Validation

[![Build Status](https://img.shields.io/travis/barbieswimcrew/zip-code-validator/master.svg?style=flat-square)](https://travis-ci.org/barbieswimcrew/zip-code-validator)
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

```bash

<?php
//...
$form = $this->createFormBuilder($address)
            ->add('zipcode', TextType::class, array(
                'constraints' => array(
                    new ZipCodeValidator\Constraints\ZipCode(array(
                        'iso' => 'DE'
                    ))
                )
            ))
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();
```

Another way would be to use the constraint as an annotation of a class property, for example:
```bash
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

>  Please consider to inject a valid ISO 3166 2-letter country code (e.g. DE, US, FR)!

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
}
```

To avoid that the validation fails in case that there's an empty value in the zip code field
you can set the `ignoreEmpty` option to `TRUE`.

```php
    /**
     * @ZipCode(getter="getCountry", ignoreEmpty=true)
     */
    protected $zipCode;
}
```


## Copying / License
This repository is distributed under the MIT License (MIT). You can find the whole license text in the [LICENSE](LICENSE) file.
