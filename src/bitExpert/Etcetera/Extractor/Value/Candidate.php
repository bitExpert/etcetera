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
namespace bitExpert\Etcetera\Extractor\Value;

use bitExpert\Etcetera\Extractor\Source\Descriptor\PropertyDescriptor;

/**
 * Class Candidate
 *
 * A candidate are used to describe possible candidates for a mapping
 * (name and occurance match)
 *
 * @package bitExpert\Etcetera\Domain\Source
 */
class Candidate
{
    /**
     * @var string
     */
    private $property;
    /**
     * @var int
     */
    private $occurance;

    /**
     * @param $property
     * @param int $occurance
     */
    public function __construct(string $property, int $occurance = 1)
    {
        $this->property = strtolower($property);
        $this->occurance = $occurance;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * Returns whether this candidate matches the given propertydescriptor
     *
     * @param PropertyDescriptor $propertyDescriptor
     * @return bool
     */
    public function matches(PropertyDescriptor $propertyDescriptor): bool
    {
        $property = $propertyDescriptor->getName();
        $occurance = $propertyDescriptor->getOccurance();

        return ($property === $this->property) && ($occurance === $this->occurance);
    }
}
