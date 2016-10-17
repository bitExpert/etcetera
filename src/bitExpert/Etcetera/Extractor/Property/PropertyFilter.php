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
namespace bitExpert\Etcetera\Extractor\Property;

use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

interface PropertyFilter
{
    /**
     * Returns whether given value should pass or not
     *
     * @param ValueDescriptor $valueDescriptor
     * @return bool
     */
    public function filter(ValueDescriptor $valueDescriptor): bool;
}
