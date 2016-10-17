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
namespace bitExpert\Etcetera\Extractor\Config;

use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;

/**
 * Class AbstractConfigReader
 */
abstract class AbstractConfigReader implements ConfigReader
{
    /**
     * {@inheritDoc}
     */
    public function fromArray(array $config) : Option
    {
        $processedConfig = [
            'entities' => [],
            'relations' => []
        ];

        if (isset($config['entities'])) {
            $entities = $config['entities'];
            foreach ($entities as $name => $entity) {
                $processedConfig['entities'][$name] = Some::create($this->processEntityConfig($entity));
            }
        }

        if (isset($config['relations'])) {
            $relations = $config['relations'];
            foreach ($relations as $name => $relation) {
                $processedConfig['relations'][$name] = Some::create($this->processRelationConfig($relation));
            }
        }

        return Some::create($processedConfig);
    }

    protected function processRelationConfig(array $relation)
    {
        $processedRelation = $this->processEntityConfig($relation);
        $processedRelation['from'] = Some::fromArraysValue($relation, 'from');
        $processedRelation['to'] = Some::fromArraysValue($relation, 'to');

        return $processedRelation;
    }

    protected function processEntityConfig(array $entity)
    {
        $processedEntity = [];

        if (isset($entity['properties'])) {
            $properties = $entity['properties'];
            $processedProperties = [];
            foreach ($properties as $name => $property) {
                $processedProperties[$name] = Some::create([
                    'candidates' => Some::fromArraysValue($property, 'candidates'),
                    'key' => Some::fromArraysValue($property, 'key'),
                    'mandatory' => Some::fromArraysValue($property, 'mandatory'),
                    'persistent' => Some::fromArraysValue($property, 'persistent'),
                    'validators' => Some::fromArraysValue($property, 'validators'),
                    'converters' => Some::fromArraysValue($property, 'converters'),
                    'filters' => Some::fromArraysValue($property, 'filters')
                ]);
            }

            $processedEntity['properties'] = Some::create($processedProperties);
        } else {
            $processedEntity['properties'] = None::create();
        }

        $processedEntity['decorators'] = Some::fromArraysValue($entity, 'decorators');
        $processedEntity['filters'] = Some::fromArraysValue($entity, 'filters');
        $processedEntity['validators'] = Some::fromArraysValue($entity, 'validators');
        return $processedEntity;
    }
}
