# Constraint Class for international Zipcode Validation

##Installation
This package uses Composer, please checkout the [composer website](https://getcomposer.org) for more information.

The following command will install `zip-code-validator` into your project. It will also add a new entry in your `composer.json` and update the `composer.lock` as well.

```bash
$ composer require barbieswimcrew/zip-code-validator
```

> This package follows the PSR-4 convention names for its classes, which means you can easily integrate `zip-code-validator` classes loading in your own autoloader.

##What now?
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

class Address
{
    /**
     * @ZipCodeValidator\Constraints\ZipCode(iso="DE")
     */
    protected $zipCode;
}
```

>  Please consider to inject a valid ISO 3166 2-letter country code (e.g. DE, US, FR)!

## Copying / License
This repository is distributed under the MIT License (MIT). You can find the whole license text in the LICENSE file.
