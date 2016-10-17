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
namespace bitExpert\Etcetera\Extractor;

class Target
{
    /**
     * @var String
     */
    private $property;

    /**
     * @param String $property
     */
    public function __construct(string $property)
    {
        $this->property = $property;
    }

    /**
     * @return String
     */
    public function getProperty(): string
    {
        return $this->property;
    }
}
