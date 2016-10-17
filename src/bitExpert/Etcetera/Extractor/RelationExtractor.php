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
use bitExpert\Etcetera\Extractor\Extract\RelationExtract;
use bitExpert\Etcetera\Extractor\Source\Descriptor\PropertyDescriptor;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

class RelationExtractor extends AbstractEntityExtractor
{
    /**
     * @var String
     */
    private $from;
    /**
     * @var String
     */
    private $to;


    /**
     * @param string $type
     * @param string $from
     * @param string $to
     * @param PropertyExtractor[] $properties
     */
    public function __construct(string $type, array $properties, string $from = null, string $to = null)
    {
        parent::__construct($type, $properties);
        $this->from = $from;
        $this->to = $to;
    }


    /**
     * @param EntityExtract[] $entityExtracts
     * @param ValueDescriptor[] $values
     * @return null|RelationExtract
     * @throws ExtractExclusionException
     */
    public function extract(array $entityExtracts, array $values)
    {
        $fromEntity = isset($entityExtracts[$this->from]) ? $entityExtracts[$this->from] : null;
        $toEntity = isset($entityExtracts[$this->to]) ? $entityExtracts[$this->to] : null;

        if ($fromEntity) {
            $fromValues = $this->buildValueDescriptorsFromPropertyExtracts($fromEntity->getPropertyExtracts(), 'from');
            $values = array_merge($values, $fromValues);
        }

        if ($toEntity) {
            $toValues = $this->buildValueDescriptorsFromPropertyExtracts($toEntity->getPropertyExtracts(), 'to');
            $values = array_merge($values, $toValues);
        }

        $propertyExtracts = $this->decorate($this->extractProperties($values), $values);

        $hasProperties = count($propertyExtracts) > 0;
        if ($this->from && !$fromEntity) {
            return null;
        }

        if ($this->to && !$toEntity) {
            return null;
        }

        if (!$hasProperties && !$fromEntity && !$toEntity) {
            return null;
        }

        return new RelationExtract($this->type, $propertyExtracts, $fromEntity, $toEntity);
    }

    private function buildValueDescriptorsFromPropertyExtracts(array $extracts, string $source)
    {
        $valueDescriptors = [];

        foreach ($extracts as $extract) {
            $name = $extract->getName();
            $value = $extract->getValue();
            $mappedName = sprintf('%s.%s', $source, $name);

            $valueDescriptors[$mappedName][] = new ValueDescriptor(
                $value,
                new PropertyDescriptor(
                    0,
                    $mappedName,
                    1
                )
            );
        }

        return $valueDescriptors;
    }
}
