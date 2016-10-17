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

use bitExpert\Etcetera\Common\Factory\AbstractTypeFactory;

class EntityFilterTypeFactory extends AbstractTypeFactory implements EntityFilterFactory
{
    public function create($type) : EntityFilter
    {
        return $this->getInstanceForType($type);
    }
}
