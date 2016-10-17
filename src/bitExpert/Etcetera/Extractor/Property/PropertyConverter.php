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

interface PropertyConverter
{
    /**
     * Converts the value of the given ValueDescriptor
     * to the desired format
     *
     * @param ValueDescriptor $valueDescriptor
     * @return mixed
     */
    public function convert(ValueDescriptor $valueDescriptor);
}
