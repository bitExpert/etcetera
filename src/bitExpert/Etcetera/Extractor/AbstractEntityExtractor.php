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

use bitExpert\Etcetera\Common\Logging\LoggerTrait;
use bitExpert\Etcetera\Extractor\Entity\EntityDecorator;
use bitExpert\Etcetera\Extractor\Entity\EntityFilter;
use bitExpert\Etcetera\Extractor\Exception\ExtractExclusionException;
use bitExpert\Etcetera\Extractor\Extract\PropertyExtract;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

/**
 * Class EntityExtractor
 * Extracts properties for an entity
 *
 * @package bitExpert\Etcetera\Extractor
 */
abstract class AbstractEntityExtractor
{
    use LoggerTrait;
    /**
     * @var String
     */
    protected $type;
    /**
     * @var PropertyExtractor[]
     */
    protected $properties;
    /**
     * @var EntityDecorator[]
     */
    protected $decorators;
    /**
     * @var EntityFilter[]
     */
    protected $filters;

    /**
     * @param String $type
     * @param PropertyExtractor[] $properties
     */
    public function __construct(string $type, array $properties)
    {
        $this->type = $type;
        $this->properties = $properties;
        $this->decorators = [];
        $this->filters = [];
    }

    /**
     * @param EntityDecorator[] $decorators
     */
    public function setDecorators(array $decorators)
    {
        $this->decorators = [];

        foreach ($decorators as $decorator) {
            $this->addDecorator($decorator);
        }
    }

    /**
     * @param EntityDecorator $decorator
     */
    public function addDecorator(EntityDecorator $decorator)
    {
        $this->decorators[] = $decorator;
    }

    /**
     * @param EntityFilter[] $filters
     */
    public function setFilters(array $filters)
    {
        $this->filters = [];

        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * @param EntityFilter $filter
     */
    public function addFilter(EntityFilter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Extracts properties from given values
     * @param ValueDescriptor[] $values
     * @return PropertyExtract[]
     * @throws ExtractExclusionException
     */
    protected function extractProperties(array $values): array
    {
        $propertyExtracts = [];
        $failed = false;
        $failedMessage = '';

        /* @var PropertyExtractor $property */
        foreach ($this->properties as $property) {
            try {
                $propertyExtract = $property->extract($values);

                if ($property->isMandatory() && is_null($propertyExtract)) {
                    $failed = true;
                    $failedMessage = sprintf(
                        'Property "%s" is mandatory but failed',
                        $propertyExtract->getName()
                    );
                    break;
                }

                if ($propertyExtract->isPersistent()) {
                    $propertyExtracts[$propertyExtract->getName()] = $propertyExtract;
                }
            } catch (ExtractExclusionException $e) {
                if ($property->isMandatory()) {
                    $this->logWarning($e->getMessage());
                    $failed = true;
                    $failedMessage = sprintf(
                        'Property "%s" is mandatory but failed: %s',
                        $property->getTarget()->getProperty(),
                        $e->getMessage()
                    );
                    break;
                }
            }
        }

        if ($failed) {
            throw new ExtractExclusionException(sprintf('Did not include row: %1$s', $failedMessage));
        }

        $propertyExtracts = $this->decorate($propertyExtracts, $values);

        if (!$this->filter($propertyExtracts, $values)) {
            throw new ExtractExclusionException('Entity has been filtered');
        }

        return $propertyExtracts;
    }

    /**
     * Returns whether this entity should be filtered or not determined by given values
     *
     * @param PropertyExtract[] $propertyExtracts
     * @param ValueDescriptor[] $values
     * @return bool
     */
    protected function filter(array $propertyExtracts, array $values): bool
    {
        foreach ($this->filters as $filter) {
            $result = $filter->filter($propertyExtracts, $values);
            if ($result !== true) {
                return false;
            }
        }

        return true;
    }

    /**
     * Decorates the entity using configured decorators
     *
     * @param PropertyExtract[] $propertyExtracts
     * @param ValueDescriptor[] $values
     * @return PropertyExtract[]
     */
    protected function decorate(array $propertyExtracts, array $values): array
    {
        foreach ($this->decorators as $decorator) {
            $decorationPropertyExtracts = $decorator->decorate($propertyExtracts, $values);
            if (!is_array($decorationPropertyExtracts)) {
                $decorationPropertyExtracts = [$decorationPropertyExtracts];
            }

            foreach ($decorationPropertyExtracts as $decorationPropertyExtract) {
                if (!is_null($decorationPropertyExtract)) {
                    $propertyExtracts[$decorationPropertyExtract->getName()] = $decorationPropertyExtract;
                }
            }
        }

        return $propertyExtracts;
    }

    /**
     * Returns the type of the entity
     * @return String
     */
    public function getType(): string
    {
        return $this->type;
    }
}
