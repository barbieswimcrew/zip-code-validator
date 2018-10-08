<?php
namespace Tests\Fixtures;

class IsoObject
{
    /** @var string */
    protected $iso;

    /**
     * TestObject constructor.
     *
     * @param string $iso
     */
    public function __construct($iso)
    {
        $this->iso = $iso;
    }

    /**
     * @return string
     */
    public function myValidationMethod()
    {
        return $this->iso;
    }
}