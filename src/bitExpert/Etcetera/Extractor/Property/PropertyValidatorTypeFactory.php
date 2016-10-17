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

use bitExpert\Etcetera\Common\Factory\AbstractTypeFactory;

class PropertyValidatorTypeFactory extends AbstractTypeFactory implements PropertyValidatorFactory
{
    public function create($type) : PropertyValidator
    {
        return $this->getInstanceForType($type);
    }
}
