<?php
declare(strict_types = 1);

/*
 * This file is part of the Etcetera package.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace bitExpert\Etcetera\Extractor\Source\Descriptor;

/**
 * Class ValueDescriptor
 * Value descriptors hold value and their property descriptor
 *
 * @package bitExpert\Etcetera\Domain\Source\Descriptor
 */
class ValueDescriptor
{
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var PropertyDescriptor
     */
    private $property;

    /**
     * @param mixed $value
     * @param PropertyDescriptor $property
     */
    public function __construct($value, PropertyDescriptor $property)
    {
        $this->value = $value;
        $this->property = $property;
    }

    /**
     * @return PropertyDescriptor
     */
    public function getProperty(): PropertyDescriptor
    {
        return $this->property;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns whether this value is applicable for given conditions
     *
     * @param string $name
     * @param int $occurance
     *
     * @return bool
     */
    public function isApplicable(string $name, int $occurance): bool
    {
        return $this->property->matches($name, $occurance);
    }
}
