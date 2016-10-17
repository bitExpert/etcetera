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
namespace bitExpert\Etcetera\Extractor\Extract;

class TargetExtract
{
    private $property;
    private $value;
    private $isKey;

    public function __construct(string $property, $value, bool $isKey)
    {
        $this->property = $property;
        $this->value = $value;
        $this->isKey = $isKey;
    }

    /**
     * Returns the value
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns whether extract is part of the key or not
     * @return bool
     */
    public function isKey(): bool
    {
        return $this->isKey;
    }
}
