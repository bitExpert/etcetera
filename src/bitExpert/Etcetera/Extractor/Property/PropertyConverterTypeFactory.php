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

/**
 * Class ConverterFactory
 */
class PropertyConverterTypeFactory extends AbstractTypeFactory implements PropertyConverterFactory
{
    public function create($type) : PropertyConverter
    {
        return $this->getInstanceForType($type);
    }
}
