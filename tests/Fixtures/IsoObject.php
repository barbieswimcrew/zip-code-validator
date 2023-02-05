<?php
namespace ZipCodeValidator\Tests\Fixtures;

class IsoObject
{
    public function __construct(protected string $iso)
    {
    }

    public function myValidationMethod(): string
    {
        return $this->iso;
    }
}
