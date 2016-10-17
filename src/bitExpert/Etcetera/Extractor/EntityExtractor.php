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

use bitExpert\Etcetera\Extractor\Exception\ExtractExclusionException;
use bitExpert\Etcetera\Extractor\Extract\EntityExtract;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

/**
 * Class EntityExtractor
 * Extracts properties for an entity
 */
class EntityExtractor extends AbstractEntityExtractor
{
    /**
     * @param ValueDescriptor[] $values
     * @return null|EntityExtract
     * @throws ExtractExclusionException
     */
    public function extract(array $values) : EntityExtract
    {
        $propertyExtracts = $this->decorate($this->extractProperties($values), $values);

        if (!count($propertyExtracts)) {
            throw new ExtractExclusionException('Did not include row because no properties could be determined');
        }

        return new EntityExtract($this->type, $propertyExtracts);
    }
}
