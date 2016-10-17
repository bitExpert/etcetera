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
use bitExpert\Etcetera\Extractor\Exception\ExtractExclusionException;
use bitExpert\Etcetera\Extractor\Extract\PropertyExtract;
use bitExpert\Etcetera\Extractor\Property\PropertyConverter;
use bitExpert\Etcetera\Extractor\Property\PropertyFilter;
use bitExpert\Etcetera\Extractor\Property\PropertyValidator;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;

/**
 * Class PropertyExtractor
 * Extracts a property for an entity
 *
 * @package bitExpert\Etcetera\Extractor
 */
class PropertyExtractor
{
    use LoggerTrait;
    /**
     * @var ValueExtractor
     */
    private $source;
    /**
     * @var Target
     */
    private $target;
    /**
     * @var PropertyValidator[]
     */
    private $validators;
    /**
     * @var PropertyConverter[]
     */
    private $converters;
    /**
     * @var PropertyFilter[]
     */
    private $filters;
    /**
     * @var bool
     */
    private $isKey;
    /**
     * @var bool
     */
    private $mandatory;
    /**
     * @var bool
     */
    private $persistent;

    /**
     * @param ValueExtractor $source
     * @param Target $target
     * @param bool $isKey
     */
    public function __construct(ValueExtractor $source, Target $target, bool $isKey)
    {
        $this->isKey = $isKey;
        $this->mandatory = $isKey;
        $this->persistent = true;
        $this->source = $source;
        $this->target = $target;
        $this->converters = [];
        $this->validators = [];
        $this->filters = [];
    }

    /**
     * @param bool $mandatory
     */
    public function setMandatory(bool $mandatory)
    {
        if (!$this->isKey) {
            $this->mandatory = (bool)$mandatory;
        }
    }

    /**
     * @param bool $persistent
     */
    public function setPersistent(bool $persistent)
    {
        $this->persistent = $persistent;
    }

    /**
     * @param PropertyValidator[] $validators
     */
    public function setValidators(array $validators)
    {
        $this->validators = [];

        foreach ($validators as $validator) {
            $this->addValidator($validator);
        }
    }

    /**
     * @param PropertyValidator $validator
     */
    public function addValidator(PropertyValidator $validator)
    {
        $this->validators[] = $validator;
    }

    /**
     * @param PropertyConverter[] $converters
     */
    public function setConverters(array $converters)
    {
        $this->converters = [];

        foreach ($converters as $converter) {
            $this->addConverter($converter);
        }
    }

    /**
     * @param PropertyConverter $converter
     */
    public function addConverter(PropertyConverter $converter)
    {
        $this->converters[] = $converter;
    }

    /**
     * @param PropertyFilter[] $filters
     */
    public function setFilters(array $filters)
    {
        $this->filters = [];

        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
    }

    /**
     * @param PropertyFilter $filter
     */
    public function addFilter(PropertyFilter $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Extracts properties from given values
     * @param ValueDescriptor[] $values
     * @return PropertyExtract
     * @throws ExtractExclusionException
     */
    public function extract(array $values): PropertyExtract
    {
        $valueDescriptor = $this->source->extract($values);

        if (!$this->validate($valueDescriptor)) {
            throw new ExtractExclusionException(sprintf(
                'Validation for row failed because of property: "%1$s", value: "%2$s"',
                $valueDescriptor->getProperty()->getName(),
                $valueDescriptor->getValue()
            ));
        }

        if (!$this->filter($valueDescriptor)) {
            throw new ExtractExclusionException(sprintf(
                'Filtered row because of property: "%1$s", value: "%2$s"',
                $valueDescriptor->getProperty()->getName(),
                $valueDescriptor->getValue()
            ));
        }

        $valueDescriptor = $this->convert($valueDescriptor);

        return new PropertyExtract(
            $this->target->getProperty(),
            $valueDescriptor->getValue(),
            $this->isKey,
            $this->persistent
        );
    }

    /**
     * Validates given ValueDescriptor using the configured validators
     * @param ValueDescriptor $valueDescriptor
     * @return bool
     */
    private function validate(ValueDescriptor $valueDescriptor): bool
    {
        foreach ($this->validators as $validator) {
            $valid = $validator->validate($valueDescriptor);
            if (!$valid) {
                return false;
            }
        }

        return true;
    }

    /**
     * Converts given value of descriptor using the configured converters
     * @param ValueDescriptor $valueDescriptor
     * @return ValueDescriptor
     */
    private function convert(ValueDescriptor $valueDescriptor): ValueDescriptor
    {
        foreach ($this->converters as $converter) {
            $value = $converter->convert($valueDescriptor);

            if ($value instanceof ValueDescriptor) {
                $value = $value->getValue();
            }

            $valueDescriptor = new ValueDescriptor($value, $valueDescriptor->getProperty());
        }

        return $valueDescriptor;
    }

    /**
     * Filters the value of the given ValueDescriptor using the configured filters
     * @param ValueDescriptor $valueDescriptor
     * @return bool
     */
    private function filter(ValueDescriptor $valueDescriptor): bool
    {
        foreach ($this->filters as $filter) {
            $pass = $filter->filter($valueDescriptor);

            if (!$pass) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isKey(): bool
    {
        return $this->isKey;
    }

    /**
     * @return bool
     */
    public function isMandatory(): bool
    {
        return $this->mandatory;
    }

    /**
     * @return bool
     */
    public function isPersistent(): bool
    {
        return $this->persistent;
    }

    /**
     * @return Target
     */
    public function getTarget(): Target
    {
        return $this->target;
    }
}
