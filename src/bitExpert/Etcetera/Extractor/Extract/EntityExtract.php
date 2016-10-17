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
namespace bitExpert\Etcetera\Extractor\Extract;

class EntityExtract implements \IteratorAggregate
{
    /**
     * @var String
     */
    private $type;
    /**
     * @var PropertyExtract[]
     */
    private $propertyExtracts;

    /**
     * @param String $type
     * @param PropertyExtract[] $propertyExtracts
     */
    public function __construct(string $type, array $propertyExtracts)
    {
        $this->type = $type;
        $this->propertyExtracts = $propertyExtracts;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return PropertyExtract[]
     */
    public function getPropertyExtracts(): array
    {
        return $this->propertyExtracts;
    }

    /**
     * @return PropertyExtract[]
     */
    public function getKeyPropertyExtracts(): array
    {
        return array_filter($this->propertyExtracts, function (PropertyExtract $propertyExtract) {
            return $propertyExtract->isKey();
        });
    }

    /**
     * @return PropertyExtract[]
     */
    public function getNonKeyPropertyExtracts(): array
    {
        return array_filter($this->propertyExtracts, function (PropertyExtract $propertyExtract) {
            return !$propertyExtract->isKey();
        });
    }

    /**
     * Returns the PropertyExtract of property with given name, null if does not exist
     * @param $name
     * @return PropertyExtract|null
     */
    public function getPropertyExtract($name)
    {
        return isset($this->propertyExtracts[$name]) ? $this->propertyExtracts[$name] : null;
    }

    /**
     * Returns the value of property with given name, null if it does not exist
     * @param $name
     * @return mixed|null
     */
    public function getPropertyValue($name)
    {
        $extract = $this->getPropertyExtract($name);
        return (null === $extract) ? $extract : $extract->getValue();
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * Returns an array having key value pairs propertyName=>propertyValue
     */
    public function toArray(): array
    {
        return array_map(function (PropertyExtract $propertyExtract) {
            return $propertyExtract->getValue();
        }, $this->propertyExtracts);
    }
}
