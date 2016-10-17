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
namespace bitExpert\Etcetera\Reader;

use bitExpert\Etcetera\Common\Observer\Observer;
use bitExpert\Etcetera\Extractor\Source\Descriptor\PropertyDescriptor;
use bitExpert\Etcetera\Extractor\Source\Descriptor\ValueDescriptor;
use bitExpert\Etcetera\Common\Logging\LoggerTrait;

/**
 * Class AbstractReader
 */
abstract class AbstractReader implements Reader
{
    use LoggerTrait;

    protected $observers;
    protected $values;

    public function __construct()
    {
        $this->observers = [];
        $this->values = [];
    }

    /**
     * @inheritdoc
     */
    public function registerObserver(Observer $observer)
    {
        $this->observers[] = $observer;
    }

    /**
     * @inheritdoc
     */
    public function unregisterObserver(Observer $observer)
    {
        foreach ($this->observers as $index => $registeredObserver) {
            if ($observer === $registeredObserver) {
                unset($this->observers[$index]);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function notifyObservers()
    {
        foreach ($this->observers as $observer) {
            $observer->update();
        }
    }

    /**
     * Describes properties and returns information using PropertyDescriptors
     *
     * @param array $properties
     * @return PropertyDescriptor[]
     */
    protected function describeProperties(array $properties)
    {
        $occurances = [];
        $descriptors = [];
        foreach ($properties as $index => $property) {
            $property = strtolower($property);

            if (!array_key_exists($property, $occurances)) {
                $occurances[$property] = 0;
            }

            $occurances[$property]++;
            $descriptors[$index] = new PropertyDescriptor($index, $property, $occurances[$property]);
        }

        return $descriptors;
    }

    /**
     * Processes given values and property descriptors to value descriptors
     *
     * @param array $propertyDescriptors
     * @param ValueDescriptor[] $values
     */
    protected function processValues(array $propertyDescriptors, array $values)
    {
        $valueDescriptors = [];

        foreach ($propertyDescriptors as $index => $propertyDescriptor) {
            $name = $propertyDescriptor->getName();

            if (!isset($values[$index])) {
                $values[$index] = null;
            }

            if (!isset($valueDescriptors[$name])) {
                $valueDescriptors[$name] = [];
            }

            $valueDescriptors[$name][] = new ValueDescriptor($values[$index], $propertyDescriptor);
        }

        $this->values = $valueDescriptors;

        $this->notifyObservers();
    }
}
