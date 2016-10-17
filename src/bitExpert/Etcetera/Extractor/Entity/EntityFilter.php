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
namespace bitExpert\Etcetera\Extractor\Entity;

use bitExpert\Etcetera\Extractor\Extract\PropertyExtract;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

interface EntityFilter
{
    /**
     * Returns whether given value should pass or not
     *
     * @param PropertyExtract[] $propertyExtracts
     * @param ValueDescriptor[] $values
     * @return bool
     */
    public function filter(array $propertyExtracts, array $values) : bool;
}
