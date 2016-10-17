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

use bitExpert\Etcetera\Extractor\Entity\EntityFilter;
use bitExpert\Etcetera\Extractor\Property\PropertyConverter;
use bitExpert\Etcetera\Extractor\Property\PropertyConverterFactory;
use bitExpert\Etcetera\Extractor\Property\PropertyFilter;
use bitExpert\Etcetera\Extractor\Property\PropertyFilterFactory;
use bitExpert\Etcetera\Extractor\Property\PropertyValidator;
use bitExpert\Etcetera\Extractor\Property\PropertyValidatorFactory;
use bitExpert\Etcetera\Extractor\Entity\EntityFilterFactory;
use bitExpert\Etcetera\Extractor\Entity\EntityDecoratorFactory;
use bitExpert\Etcetera\Extractor\Value\Candidate;
use PhpOption\Option;

/**
 * Class StandardExtractorFactory
 * Creates an instance of StandardExtractor according to given definition
 */
class StandardExtractorFactory
{
    /**
     * @var PropertyConverterFactory
     */
    protected $propertyConverterFactory;
    /**
     * @var PropertyValidatorFactory
     */
    protected $propertyValidatorFactory;
    /**
     * @var PropertyFilterFactory
     */
    protected $propertyFilterFactory;
    /**
     * @var EntityFilterFactory
     */
    protected $entityFilterFactory;
    /**
     * @var EntityDecoratorFactory
     */
    protected $entityDecoratorFactory;

    /**
     * @param PropertyValidatorFactory $propertyValidatorFactory
     */
    public function setPropertyValidatorFactory(PropertyValidatorFactory $propertyValidatorFactory)
    {
        $this->propertyValidatorFactory = $propertyValidatorFactory;
    }

    /**
     * @param PropertyConverterFactory $propertyConverterFactory
     */
    public function setPropertyConverterFactory(PropertyConverterFactory $propertyConverterFactory)
    {
        $this->propertyConverterFactory = $propertyConverterFactory;
    }

    /**
     * @param PropertyFilterFactory $propertyFilterFactory
     */
    public function setPropertyFilterFactory(PropertyFilterFactory $propertyFilterFactory)
    {
        $this->propertyFilterFactory = $propertyFilterFactory;
    }

    /**
     * @param EntityFilterFactory $entityFilterFactory
     */
    public function setEntityFilterFactory(EntityFilterFactory $entityFilterFactory)
    {
        $this->entityFilterFactory = $entityFilterFactory;
    }

    /**
     * @param EntityDecoratorFactory $entityDecoratorFactory
     */
    public function setEntityDecoratorFactory(EntityDecoratorFactory $entityDecoratorFactory)
    {
        $this->entityDecoratorFactory = $entityDecoratorFactory;
    }

    /**
     * @inheritdoc
     * @return Extractor
     */
    public function create(Option $config) : Extractor
    {
        $config = $config->getOrThrow(new \InvalidArgumentException('No valid configuration provided'));
        $relations = $this->createRelationExtractors($config['relations']);
        $entities = $this->createEntityExtractors($config['entities']);

        return new StandardExtractor($entities, $relations);
    }

    /**
     * @param array $configs
     * @return RelationExtractor[]
     */
    protected function createRelationExtractors(array $configs)
    {
        $relations = [];
        foreach ($configs as $type => $relationConfig) {
            $relations[$type] = $this->createRelationExtractor($type, $relationConfig->get());
        }
        return $relations;
    }

    /**
     * @param $config
     * @return EntityExtractor[]
     */
    protected function createEntityExtractors($config)
    {
        $entities = [];
        /** @var Option $entityConfig */
        foreach ($config as $type => $entityConfig) {
            $entities[$type] = $this->createEntityExtractor($type, $entityConfig->get());
        }

        return $entities;
    }

    /**
     * @param String $type
     * @param String $config
     * @return RelationExtractor
     */
    protected function createRelationExtractor($type, $config)
    {
        $properties = [];

        /** @var Option $propertiesConfig */
        $propertiesConfig = $config['properties'];
        /** @var Option $decoratorsConfig */
        $decoratorsConfig = $config['decorators'];
        /** @var Option $filtersConfig */
        $filtersConfig = $config['filters'];
        /** @var Option $fromConfig */
        $fromConfig = $config['from'];
        /** @var Option $toConfig */
        $toConfig = $config['to'];

        /** @var Option $propertyConfig */
        foreach ($propertiesConfig->getOrElse([]) as $name => $propertyConfig) {
            $properties[$name] = $this->createPropertyExtractor($name, $propertyConfig->get());
        }

        $decorators = $this->createEntityDecorators($decoratorsConfig->getOrElse([]));
        $filters = $this->createEntityFilters($filtersConfig->getOrElse([]));
        $from = $fromConfig->getOrElse(null);
        $to = $toConfig->getOrElse(null);

        $relation = new RelationExtractor($type, $properties, $from, $to);
        $relation->setDecorators($decorators);
        $relation->setFilters($filters);

        return $relation;
    }

    /**
     * @param String $type
     * @param array $config
     * @return EntityExtractor
     */
    protected function createEntityExtractor($type, $config)
    {
        $properties = [];
        /** @var Option $propertiesConfig */
        $propertiesConfig = $config['properties'];
        /** @var Option $decoratorsConfig */
        $decoratorsConfig = $config['decorators'];
        /** @var Option $filtersConfig */
        $filtersConfig = $config['filters'];

        /** @var Option $propertyConfig */
        foreach ($propertiesConfig->getOrElse([]) as $name => $propertyConfig) {
            $properties[$name] = $this->createPropertyExtractor($name, $propertyConfig->get());
        }

        $decorators = $this->createEntityDecorators($decoratorsConfig->getOrElse([]));
        $filters = $this->createEntityFilters($filtersConfig->getOrElse([]));

        $entity = new EntityExtractor($type, $properties);
        $entity->setDecorators($decorators);
        $entity->setFilters($filters);

        return $entity;
    }

    /**
     * @param String $name
     * @param array $config
     * @return PropertyExtractor
     */
    protected function createPropertyExtractor($name, $config)
    {
        /** @var Option $candidatesConfig */
        $candidatesConfig = $config['candidates'];
        /** @var Option $validatorsConfig */
        $validatorsConfig = $config['validators'];
        /** @var Option $convertersConfig */
        $convertersConfig = $config['converters'];
        /** @var Option $filtersConfig */
        $filtersConfig = $config['filters'];
        /** @var Option $keyConfig */
        $keyConfig = $config['key'];
        /** @var Option $mandatoryConfig */
        $mandatoryConfig = $config['mandatory'];
        /** @var Option $persistentConfig */
        $persistentConfig = $config['persistent'];

        $src = $this->createValueExtractor($candidatesConfig->get());
        $target = $this->createTarget($name);

        $validators = $this->createPropertyValidators($validatorsConfig->getOrElse([]));
        $converters = $this->createPropertyConverters($convertersConfig->getOrElse([]));
        $filters = $this->createPropertyFilters($filtersConfig->getOrElse([]));

        $isKey = $this->convertToBoolean($keyConfig->getOrElse(false));
        $mandatory = $this->convertToBoolean($mandatoryConfig->getOrElse(false));
        $persistent = $this->convertToBoolean($persistentConfig->getOrElse(true));

        $property = new PropertyExtractor($src, $target, $isKey);
        $property->setConverters($converters);
        $property->setValidators($validators);
        $property->setFilters($filters);
        $property->setMandatory($mandatory);
        $property->setPersistent($persistent);

        return $property;
    }

    /**
     * @param array $config
     * @return ValueExtractor
     */
    protected function createValueExtractor($config)
    {
        $candidates = $this->createCandidates($config);

        return new ValueExtractor($candidates);
    }

    /**
     * @param string $name
     * @return Target
     */
    protected function createTarget($name)
    {
        $target = new Target($name);

        return $target;
    }

    /**
     * @param array $config
     * @return Candidate[]
     */
    protected function createCandidates($config)
    {
        $candidates = [];
        foreach ($config as $property => $occurence) {
            $candidates[] = new Candidate($property, $occurence);
        }

        return $candidates;
    }

    /**
     * @param array $types
     * @return PropertyValidator[]
     */
    protected function createPropertyValidators(array $types)
    {
        if (!count($types)) {
            return [];
        }

        if (!$this->propertyValidatorFactory) {
            throw new \InvalidArgumentException(
                'You are trying to use property validators but you don\'t have defined a PropertyValidatorFactory'
            );
        }

        $instances = [];
        foreach ($types as $type) {
            $instances[] = $this->propertyValidatorFactory->create($type);
        }

        return $instances;
    }

    /**
     * @param array $types
     * @return PropertyConverter[]
     */
    protected function createPropertyConverters(array $types)
    {
        if (!count($types)) {
            return [];
        }

        if (!$this->propertyConverterFactory) {
            throw new \InvalidArgumentException(
                'You are trying to use property converters but you don\'t have defined a PropertyConverterFactory'
            );
        }

        $instances = [];
        foreach ($types as $type) {
            $instances[] = $this->propertyConverterFactory->create($type);
        }

        return $instances;
    }

    /**
     * @param array $types
     * @return PropertyFilter[]
     */
    protected function createPropertyFilters(array $types)
    {
        if (!count($types)) {
            return [];
        }

        if (!$this->propertyFilterFactory) {
            throw new \InvalidArgumentException(
                'You are trying to use property filters but you don\'t have defined a PropertyFilterFactory'
            );
        }

        $instances = [];
        foreach ($types as $type) {
            $instances[] = $this->propertyFilterFactory->create($type);
        }

        return $instances;
    }

    /**
     * @param array $types
     * @return EntityFilter[]
     */
    protected function createEntityFilters(array $types)
    {
        if (!count($types)) {
            return [];
        }

        if (!$this->entityFilterFactory) {
            throw new \InvalidArgumentException(
                'You are trying to use entity filters but you don\'t have defined an EntityFilterFactory'
            );
        }

        $instances = [];
        foreach ($types as $type) {
            $instances[] = $this->entityFilterFactory->create($type);
        }

        return $instances;
    }

    /**
     * @param array $types
     * @return EntityFilter[]
     */
    protected function createEntityDecorators(array $types)
    {
        if (!count($types)) {
            return [];
        }

        if (!$this->entityDecoratorFactory) {
            throw new \InvalidArgumentException(
                'You are trying to use entity decorators but you don\'t have defined an EntityDecoratorFactory'
            );
        }

        $instances = [];
        foreach ($types as $type) {
            $instances[] = $this->entityDecoratorFactory->create($type);
        }

        return $instances;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function convertToBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }
        return (strtolower($value) === 'true' || (bool)$value);
    }
}
