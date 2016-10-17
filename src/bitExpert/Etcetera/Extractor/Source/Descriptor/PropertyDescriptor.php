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
 * Class PropertyDescriptor
 *
 * Property descriptors describe properties of source objects
 * holding their index, occurence and name
 *
 * @package bitExpert\Etcetera\Domain\Source\Descriptor
 */
class PropertyDescriptor
{
    /**
     * @var int
     */
    private $index;
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $occurance;

    /**
     * @param int $index
     * @param string $name
     * @param int $occurance
     */
    public function __construct(int $index, string $name, int $occurance = 1)
    {
        $this->index = $index;
        $this->name = $name;
        $this->occurance = (int) $occurance;
    }

    /**
     * Returns whether the given name and occurance match the desciptor's ones
     *
     * @param string $name
     * @param int $occurance
     * @return bool
     */
    public function matches(string $name, int $occurance): bool
    {
        return ((strtolower($this->name) === strtolower($name)) && ($this->occurance === (int) $occurance));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getOccurance(): int
    {
        return $this->occurance;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }
}
